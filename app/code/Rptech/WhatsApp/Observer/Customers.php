<?php

namespace Rptech\WhatsApp\Observer;

use Magento\Framework\Event\ObserverInterface;

class Customers implements ObserverInterface
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
        \Magecomp\Mobilelogin\Helper\Apicall $mobileHelper,
        \Rptech\Communication\Helper\Data $rptechHelper
    ) {
        $this->logger = $logger;
        $this->mobileHelper = $mobileHelper;
        $this->rptechHelper = $rptechHelper;
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * Handler for 'customer_register' event
     *
     * @param  Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $customer = $observer->getEvent()->getData('customer');
        $customerId = $customer->getId();
        $number = '';
        $customer = $this->customerRepository->getById($customerId);
        $customerAttributeData = $customer->__toArray();
        //$this->logger->info(print_r($customerAttributeData,1));
        //$this->logger->info(print_r($_POST,1));
        
        if(isset($customerAttributeData['custom_attributes']['mobilenumber']['value'])) {
            $number = $customerAttributeData['custom_attributes']['mobilenumber']['value'];
        } else if($customer->getDefaultShipping()) {
            $sid = $customer->getDefaultShipping();
            $ship_addr = $this->addressRepository->getById($sid);
            $number = $ship_addr->getTelephone();
        } else if($customer->getDefaultBilling()) {
            $bid = $customer->getDefaultBilling();
            $bill_addr = $this->addressRepository->getById($bid);
            $number = $bill_addr->getTelephone();
        }

        if(!empty($number)) {
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
            $link = $storeUrl.'customer/account/forgotpassword/';

            $this->logger->info("WhatsApp Customers.PHP sending WA message to ".$number);
            $resp = $this->rptechHelper->sendRegistrationWhatsAppMessageToCustomer($number, $name, $link);
            if($resp) {
                $resp = json_decode($resp);
                if(!is_object($resp) || $resp->status!="processing") {
                    $this->logger->info("Some error sending WhatsApp message of Registration");
                    $this->logger->info(gettype($resp));
                    $this->logger->info(print_r($resp,1));
                }
                $this->logger->info("WhatsApp Customers.PHP message sent");
            } else {
                $this->logger->info("Registration message on WhatsApp did not send.");
            }
        } else {
            $this->logger->info("WhatsApp Customers.PHP - No Number");
        }
        return;
    }
}
