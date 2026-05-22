<?php

namespace Rptech\WhatsApp\Observer;

use Magento\Framework\Event\ObserverInterface;
//use Magento\Sales\Model\Order\Invoice as Invoice1;

class Creditmemo implements ObserverInterface
{
    protected $logger;
    protected $customerRepository;
    protected $addressRepository;
    protected $storeManager;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Rptech\Communication\Helper\Data $rptechHelper
    ) {
        $this->logger = $logger;
        $this->rptechHelper = $rptechHelper;
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
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
                    $number = "+".$number;
                }
            } else if(strlen($number)<=10) {
                $number = "+91".$number;
            }
            
            $name = isset($customerAttributeData['firstname']) ? $customerAttributeData['firstname'] : '';
            $name .= isset($customerAttributeData['lastname']) ? ' '.$customerAttributeData['lastname'] : '';
            $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
            $link = $storeUrl.'sales/guest/form/';

            $this->logger->info("Rptech_WhatsApp Creditmemo.PHP sending message to ".$number);
            $resp = $this->rptechHelper->sendRefundWhatsAppMessageToCustomer($number, $name, $orderIncrementId, $link);
            if($resp) {
                $resp = json_decode($resp);
                if(!is_object($resp) || $resp->status!="processing") {
                    $this->logger->info("Some error sending WhatsApp message of Registration");
                    $this->logger->info(gettype($resp));
                    $this->logger->info(print_r($resp,1));
                }
                $this->logger->info("Rptech_WhatsApp Creditmemo.PHP message sent");
            } else {
                $this->logger->info("Refund message on WhatsApp did not send.");
            }
        } else {
            $this->logger->info("Rptech_WhatsApp Creditmemo.PHP Number not found");
        }
        return;
    }
}
