<?php

namespace Rptech\SMS\Observer;

use Magento\Framework\Event\ObserverInterface;

class Customers implements ObserverInterface
{
    const REGISTRATION_TEMPLATE_ID = "rpt_general/sms/smstxt_register_id";
    const REGISTRATION_TEMPLATE_TEXT = "rpt_general/sms/smstxt_register";

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

    /**
     * Handler for 'customer_register' event
     *
     * @param  Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->info("The observer for SMS message (customer registration) started");
        $customer = $observer->getEvent()->getData('customer');
        $customerId = $customer->getId();
        //echo $customerId;
        $number = '';
        $customer = $this->customerRepository->getById($customerId);
        $customerAttributeData = $customer->__toArray();
        //$this->logger->info(print_r($customerAttributeData,1));
        //$this->logger->info(print_r($_POST,1));
        
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
            $message = "Registered successfully";
            if($this->rptechHelper->getConfig(self::REGISTRATION_TEMPLATE_TEXT)) {
                $message = $this->rptechHelper->getConfig(self::REGISTRATION_TEMPLATE_TEXT);
                $name = isset($customerAttributeData['firstname']) ? $customerAttributeData['firstname'] : '';
                $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                $link = $storeUrl.'customer/account/forgotpassword/';
                //$link = $storeUrl;
                if(!empty($name)) {
                    $message = str_replace("[Name]", $name, $message);
                }
                $message = str_replace("[link]", $link, $message);
            }
            $this->logger->info("SMS of Registration sending to ".$number);
            $result = $this->mobileHelper->curlApiCall($message, $number, $this->rptechHelper->getConfig(self::REGISTRATION_TEMPLATE_ID));
            $this->logger->info(print_r($result,1));
            if($result && $result!="error") {
                $this->logger->info("SMS on new registration sent");
            }
        }
        else {
            $this->logger->info("SMS of Registration - No Number");
        }
        $this->logger->info("The observer for SMS message (customer registration) finished");
        return;
    }
}
