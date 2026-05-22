<?php

namespace Rptech\SMS\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;

class OrderPlace
{
    const ORDER_NOTIFICATION_TEMPLATE_ID = "rpt_general/sms/smstxt_neworder_id";
    const ORDER_NOTIFICATION_TEMPLATE_TEXT = "rpt_general/sms/smstxt_neworder";

    private $customerRepository;
    private $helper;
    private $rptechHelper;
    private $logger;
    private $session;
    private $storeManager;
    
    public function __construct(
            \Psr\Log\LoggerInterface $logger,
            \Magento\Customer\Model\Session $session,
            \Magecomp\Mobilelogin\Helper\Apicall $mobileHelper,
            \Rptech\General\Helper\Data $rptechHelper,
            \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
            \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->customerRepository = $customerRepository;
        $this->helper = $mobileHelper;
        $this->rptechHelper = $rptechHelper;
        $this->logger = $logger;
        $this->session = $session;
        $this->storeManager = $storeManager;
    }
    
    /**
     * @param OrderManagementInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterPlace(
        OrderManagementInterface $subject,
        OrderInterface $order
    ) {
        $this->logger->info("The observer (plugin) for SMS message (order place) started");
        if($this->session->getCustomer()) {
            $customer = $this->session->getCustomer();
            $cid = $customer->getId();
            $number = '';
            $customer2 = $this->customerRepository->getById($cid);
            $customerAttributeData = $customer2->__toArray();
            if(isset($customerAttributeData['custom_attributes']['mobilenumber']['value'])) {
                $number = $customerAttributeData['custom_attributes']['mobilenumber']['value'];
            } else if($customer->getPrimaryShippingAddress()) {
                $number = $customer->getPrimaryShippingAddress()->getTelephone();
            } else if($customer->getPrimaryBillingAddress()) {
                $number = $customer->getPrimaryBillingAddress()->getTelephone();
            }
            if(!empty($number)) {
                if(strlen($number)==12 || strlen($number)>10) {
                    if(substr($number, 0, 2)=="91") {
                        $number = substr($number, 2);
                    }
                }
                $orderId = $order->getIncrementId();
                $message = "Order placed successfully - ".$orderId;
                if($this->rptechHelper->getConfig(self::ORDER_NOTIFICATION_TEMPLATE_TEXT)) {
                    $message = $this->rptechHelper->getConfig(self::ORDER_NOTIFICATION_TEMPLATE_TEXT);
                    $name = $customer->getFirstname()?: '';
                    $name .= $customer->getLastname()? ' '.$customer->getLastname() : '';
                    $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                    $link = $storeUrl.'sales/guest/form/';
                    //$link = $storeUrl;
                    if(!empty($name)) {
                        $message = str_replace("[Name]", $name, $message);
                    }
                    $message = str_replace("[order ID]", $orderId, $message);
                    $message = str_replace("[link]", $link, $message);
                }
                $result = $this->helper->curlApiCall($message, $number, $this->rptechHelper->getConfig(self::ORDER_NOTIFICATION_TEMPLATE_ID));
            }
        }
        $this->logger->info("The observer (plugin) for SMS message (order place) finished");
        return $order;
    }
}