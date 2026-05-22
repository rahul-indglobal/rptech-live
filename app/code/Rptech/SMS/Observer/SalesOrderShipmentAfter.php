<?php

namespace Rptech\SMS\Observer;

use Magento\Framework\Event\ObserverInterface;

class SalesOrderShipmentAfter implements ObserverInterface
{
    const SHIPMENT_NOTIFICATION_TEMPLATE_ID = "rpt_general/sms/smstxt_shipment_id";
    const SHIPMENT_NOTIFICATION_TEMPLATE_TEXT = "rpt_general/sms/smstxt_shipment";

    protected $addressRepository;
    protected $customerRepository;
    protected $helper;
    protected $rptechHelper;
    protected $logger;
    protected $session;
    protected $storeManager;
    
    public function __construct(
            \Psr\Log\LoggerInterface $logger,
            \Magento\Customer\Model\Session $session,
            \Magecomp\Mobilelogin\Helper\Apicall $mobileHelper,
            \Rptech\General\Helper\Data $rptechHelper,
            \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
            \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
            \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->helper = $mobileHelper;
        $this->rptechHelper = $rptechHelper;
        $this->logger = $logger;
        $this->session = $session;
        $this->storeManager = $storeManager;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->info("The observer for SMS message (order shipment) started");
        $shipment = $observer->getEvent()->getShipment();
        /** @var \Magento\Sales\Model\Order $order */
        $order = $shipment->getOrder();
        
        if ($order->getCustomerId()) {
            $cid = $order->getCustomerId();
            $number = '';
            $customer = $this->customerRepository->getById($cid);
            $customerAttributeData = $customer->__toArray();
            
            if (isset($customerAttributeData['custom_attributes']['mobilenumber']['value'])) {
                $number = $customerAttributeData['custom_attributes']['mobilenumber']['value'];
            } else if ($customer->getDefaultShipping()) {
                $sid = $customer->getDefaultShipping();
                $ship_addr = $this->addressRepository->getById($sid);
                $number = $ship_addr->getTelephone();
            } else if ($customer->getDefaultBilling()) {
                $bid = $customer->getDefaultBilling();
                $bill_addr = $this->addressRepository->getById($bid);
                $number = $bill_addr->getTelephone();
            }
            if (!empty($number)) {
                if (strlen($number)==12 || strlen($number)>10) {
                    if (substr($number, 0, 2)=="91") {
                        $number = substr($number, 2);
                    }
                }
                $orderId = $order->getIncrementId();
                $message = "Shipment created successfully - ".$orderId;
                if ($this->rptechHelper->getConfig(self::SHIPMENT_NOTIFICATION_TEMPLATE_TEXT)) {
                    $message = $this->rptechHelper->getConfig(self::SHIPMENT_NOTIFICATION_TEMPLATE_TEXT);
                    $name = isset($customerAttributeData['firstname']) ? $customerAttributeData['firstname'] : '';
                    $name .= isset($customerAttributeData['lastname']) ? ' '.$customerAttributeData['lastname'] : '';
                    $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                    $link = $storeUrl.'sales/guest/form/';
                    //$link = $storeUrl;
                    if (!empty($name)) {
                        $message = str_replace("[Name]", $name, $message);
                    }
                    $message = str_replace("[order ID]", $orderId, $message);
                    $message = str_replace("[link]", $link, $message);
                }
                $result = $this->helper->curlApiCall($message, $number, $this->rptechHelper->getConfig(self::SHIPMENT_NOTIFICATION_TEMPLATE_ID));
                $this->logger->info(print_r($result,1));
                if($result && $result!="error") {
                    $this->logger->info("SMS of order shipment sent");
                }
            }
        }
        $this->logger->info("The observer for SMS message (order shipment) finished");
    }
}