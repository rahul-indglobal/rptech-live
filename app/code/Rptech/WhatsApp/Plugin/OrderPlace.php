<?php

namespace Rptech\WhatsApp\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;

class OrderPlace
{
    private $customerRepository;
    private $helper;
    private $logger;
    private $session;
    private $rptHelper;
    private $productRepositoryInterfaceFactory;
    
    public function __construct(
            \Psr\Log\LoggerInterface $logger,
            \Magento\Customer\Model\Session $session,
            \Rptech\General\Helper\Data $rptechHelper,
            \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Rptech\Communication\Helper\Data $rptHelper,
            \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryInterfaceFactory)
    {
        $this->customerRepository = $customerRepository;
        $this->helper = $rptechHelper;
        $this->logger = $logger;
        $this->session = $session;
        $this->storeManager = $storeManager;
        $this->rptHelper = $rptHelper;
        $this->productRepositoryInterfaceFactory = $productRepositoryInterfaceFactory;
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
        $this->logger->info("The observer (plugin) for WhatsApp message (order place) started");
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
                        $number = "+".$number;
                    }
                } else if(strlen($number)<=10) {
                    $number = "+91".$number;
                }
                $this->logger->info("Number processed to - ".$number);

                $orderId = $order->getIncrementId();
                $name = $customer->getFirstname()?: '';
                $name .= $customer->getLastname()? ' '.$customer->getLastname() : '';
                $this->logger->info("Order Id - ".$orderId);
                $this->logger->info("Customer Name - ".$name);
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                $link = $storeUrl.'sales/guest/form/';
                $img = '';
                $items = $order->getAllItems();
                //foreach ($items as $item) {
                $this->logger->info("No. of order items - ".count($items));
                if(count($items)>=1) {
                    // var_dump($item->getData());
                    $pid = $items[0]->getProductId();
                    $product = $this->productRepositoryInterfaceFactory->create()->getById($pid);
                    $img = $product->getData('image');
                    $this->logger->info("Product ID - ".$pid);
                    //$product->getData('thumbnail');
                    //$product->getData('small_image');
                    if($img!="no_selection") {
                        $img = $mediaUrl."catalog/product/".$img;
                    }
                }
                if($img=="" || $img=="no_selection") {
                    $img = $mediaUrl."email/logo/stores/1/logo.png";
                }
                $this->logger->info("Image - ".$img);

                $resp = $this->rptHelper->sendNewOrderWhatsAppMessageToCustomer($number, $name, $orderId, $img, $link);
                if($resp) {
                    $resp = json_decode($resp);
                    if(!is_object($resp) || $resp->status!="processing") {
                        $this->logger->info("Some error sending WhatsApp message of New Order");
                        //$this->logger->info(print_r($resp,1));
                        $this->logger->info($resp->message);
                    }
                } else {
                    $this->logger->info("New order message on WhatsApp did not send.");
                }
            }
        }
        $this->logger->info("The observer (plugin) for WhatsApp message (order place) finished");
        return $order;
    }
}