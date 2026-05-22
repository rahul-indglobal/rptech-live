<?php

/**
 * Created by lalita.
 */

namespace Lalita\Mysmsmodule\Observer;

use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Sales\Model\Order;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class OrderSave
 * @package Magenest\SmsMarketing\Observer\Order
 */
class Users implements ObserverInterface {

    protected $_logger;
    protected $scopeConfig;
    protected $_currCustomer;
    protected $order;
    protected $customer;


    /**
     * @param Textlocal $textlocal
     * @param CurrentCustomer $currentCustomer
     * @param Order $order
     * @param Customer $customer
     */
    public function __construct(
     \Psr\Log\LoggerInterface $logger, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
      
        $this->_logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Handler for 'customer_logout' event.
     *
     * @param  Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $customer = $observer->getEvent()->getCustomer();
        $customerEmail = $customer->getEmail();
        $customerId = $customer->getId();
       $customerName = $customer->getFirstname();
       $mobile_number = $customer->getCustomAttribute('mobile_number')->getValue();
      
        
        $this->_logger->info(print_r($customerEmail));

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($orderId[0]);
        $orderTotal = $order->getGrandTotal();

        $customer = $this->customer->load($order->getCustomerId());
      //  $this->_logger->info(print_r('customerdata-->'));
       // $this->_logger->info(print_r($customer->getData()));
        //  echo "<pre>"; print_r($customer->getData());
        $addressObj = $order->getShippingAddress()->getData();


        //customer data
        $phoneNumber = $addressObj['telephone'];
         
         //$this->_logger->info(print_r('$phoneNumber'));
       //  $this->_logger->info(print_r($phoneNumber));
         
        $increment_id = $order->getIncrementId();
        $customerName = $customer->getFirstname();

//        $replaceString = [
//            '{{order_id}}' => $increment_id,
//            '{{customer_name}}' => $customerName,
//            '{{order_base_grand_totals}}' => $orderTotal,
//            '{{old_status}}' => $statusBefore,
//            '{{new_status}}' => $statusAfter
//        ];
        $this->_logger->info('called from order save function');


        //send sms

        $smsUrl = $this->scopeConfig->getValue('section/group/smsUrl', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $smsUserName = $this->scopeConfig->getValue('section/group/smsUserName', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $smsPassword = $this->scopeConfig->getValue('section/group/smsPassword', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $smsRoute = $this->scopeConfig->getValue('section/group/smsRoute', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $smsSender = $this->scopeConfig->getValue('section/group/smsSender', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $mobile = $phoneNumber;
      //  $this->_logger->info($mobile);
        $message = "Your order successfully place.OrderID =$orderId[0].Order Total=$orderTotal";

       $this->_logger->info($message);

        $url = $smsUrl . '?username=' . $smsUserName . '&password=' . $smsPassword . '&route=' . $smsRoute . '&sender=' . $smsSender . '&mobile[]=' . $mobile . '&message[]=' . $message;

       // $data = file_get_contents("http://vas.mobilogi.com/api.php?username=rashe&password=pass1234&route=1&sender=RPTECH&mobile[]=9029398886&message[]=fromcodeobserver");
        $data = file_get_contents("$url");
        $this->_logger->info("===>" . $data);



//        if ($phoneNumber && $status && isset($content)) {
//            $newContent = strtr($content, $replaceString);
//            if ($this->_nexmo->isEnabled()) {
//                $this->_nexmo->sendSMS($newContent, $phoneNumber);
//            }
//            if ($this->_textlocal->isEnabled()) {
//                $this->_textlocal->sendSMS($newContent, $phoneNumber);
//            }
//            if ($this->_voodoo->isEnabled()) {
//                $this->_voodoo->sendSMS($newContent, $phoneNumber);
//            }
//            if ($this->_textmarketer->isEnabled()) {
//                $this->_textmarketer->sendSMS($newContent, $phoneNumber);
//            }
//            if ($this->_twilio->isEnabled()) {
//                $this->_twilio->sendSMS($newContent, $phoneNumber);
//            }
//        }
        return $this;
    }

}
