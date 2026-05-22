<?php

namespace Rptech\Communication\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    private $logger;
    private $session;
    
    public function __construct(
            \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            \Psr\Log\LoggerInterface $logger,
            \Magento\Customer\Model\Session $session)
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
        $this->session = $session;
    }
    
    public function execute() {
        phpinfo();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $api = $objectManager->get('\Rptech\Communication\Helper\Data');
        $mobapi = $objectManager->get('\Magecomp\Mobilelogin\Helper\Apicall');
        $number = '9724479711';
        //echo "Sending message from Live site to ".$number."<br>";

        $str = "Hi Yogesh Timbaliya, your order 000001600 has now been shipped. Track your order here http://rptechindia.in/sales/guest/form/ Contact support@rptechindia.com if you need any help. - RP tech";
        /*$result = $mobapi->curlApiCall($str, $number, 1207163662499893318);
        print_r($result);*/
        die(".....End");
        
        $name = 'Yogesh Timbaliya';
        $storeUrl = "https://rptechindia.in/";
        $link = $storeUrl.'sales/guest/form/';

        if(!empty($number)) {
            if(strlen($number)==12 || strlen($number)>10) {
                if(substr($number, 0, 2)=="91") {
                    $number = "+".$number;
                }
            } else if(strlen($number)<=10) {
                $number = "+91".$number;
            }
            echo "<br>Number processed to - ".$number;
            $oid = "2403";
            $order = $objectManager->create('Magento\Sales\Model\Order')->load($oid);
            $productRepositoryInterface = $objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
            $orderId = $order->getIncrementId();
            echo "<br>Order Id - ".$orderId;
            echo "<br>Customer Name - ".$name;
            $link = 'https://rptechindia.in/sales/guest/form/';
            $img = '';
            $items = $order->getAllItems();
            //foreach ($items as $item) {
            echo "<br>No. of order items - ".count($items);
            if(count($items)>=1) {
                // var_dump($item->getData());
                $pid = $items[0]->getProductId();
                $product = $productRepositoryInterface->getById($pid);
                $img = $product->getData('image');
                echo "<br>Product ID - ".$pid;
                //$product->getData('thumbnail');
                //$product->getData('small_image');
                if($img!="no_selection") {
                    $img = $storeUrl.'media/catalog/product/'.$img;
                }
            }
            if($img=="" || $img=="no_selection") {
                $img = $storeUrl."media/email/logo/stores/1/logo.png";
            }
            echo "<br>Image - ".$img;

            $resp = $api->sendNewOrderWhatsAppMessageToCustomer($number, $name, $orderId, $img, $link);
            if($resp) {
                $resp = json_decode($resp);
                if(!is_object($resp) || $resp->status!="processing") {
                    echo "<br>Some error sending WhatsApp message of New Order";
                    echo "<pre>";
                    print_r($resp);
                    echo "</pre>";
                    echo "<br>".$resp->message;
                } else {
                    echo "<br>Message sent";
                }
            } else {
                echo "<br>New order message on WhatsApp did not send.";
            }
        }
        die(0);
    }
}