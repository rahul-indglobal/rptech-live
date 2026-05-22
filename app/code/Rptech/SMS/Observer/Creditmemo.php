<?php

namespace Rptech\SMS\Observer;

use Magento\Framework\Event\ObserverInterface;
//use Magento\Sales\Model\Order\Invoice as Invoice1;

class Creditmemo implements ObserverInterface
{
    const REFUND_TEMPLATE_ID = "rpt_general/sms/smstxt_refund_id";
    const REFUND_TEMPLATE_TEXT = "rpt_general/sms/smstxt_refund";

    protected $logger;
    protected $customerRepository;
    protected $addressRepository;
    protected $storeManager;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magecomp\Mobilelogin\Helper\Apicall $mobileHelper,
        \Rptech\General\Helper\Data $rptechHelper
    ) {
        $this->logger = $logger;
        $this->mobileHelper = $mobileHelper;
        $this->rptechHelper = $rptechHelper;
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->info("The observer for SMS message (order refund) started");
        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();

        /** @var \Magento\Sales\Model\Order $order */
        $order = $creditmemo->getOrder();

        $orderIncrementId = $order->getIncrementId();
        $customerId = $order->getCustomerId();

        $number = '';
        $customer = $this->customerRepository->getById($customerId);
        $customerAttributeData = $customer->__toArray();

        if(isset($customerAttributeData['custom_attributes']['mobilenumber']['value'])) {
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
            if(strlen($number)==12 || strlen($number)>10) {
                if(substr($number, 0, 2)=="91") {
                    $number = substr($number, 2);
                }
            }
            if($this->rptechHelper->getConfig(self::REFUND_TEMPLATE_TEXT)) {
                $message = $this->rptechHelper->getConfig(self::REFUND_TEMPLATE_TEXT);
                $name = isset($customerAttributeData['firstname']) ? $customerAttributeData['firstname'] : '';
                $name .= isset($customerAttributeData['lastname']) ? ' '.$customerAttributeData['lastname'] : '';
                $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                $link = $storeUrl.'sales/guest/form/';
                //$link = $storeUrl;
                if(!empty($name)) {
                    $message = str_replace("[Name]", $name, $message);
                }
                $message = str_replace("[order ID]", $orderIncrementId, $message);
                $message = str_replace("[link]", $link, $message);
            }
            $this->logger->info("SMS of Refund sending to ".$number);
            $result = $this->mobileHelper->curlApiCall($message, $number, $this->rptechHelper->getConfig(self::REFUND_TEMPLATE_ID));
            $this->logger->info(print_r($result,1));
            if($result && $result!="error") {
                $this->logger->info("SMS of Refund sent");
            }
        } else {
            $this->logger->info("SMS of Refund - No Number");
        }
        $this->logger->info("The observer for SMS message (order refund) finished");
        return;
    }
}
