<?php

namespace Lalita\Mysmsmodule\Observer;

use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Sales\Model\Order;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class BeforeOrderPlace implements ObserverInterface {

	protected $_logger;
	protected $scopeConfig;

	/**
	 * @var CurrentCustomer
	 */
	protected $_currCustomer;

	/**
	 * @var CurrentCustomer
	 */
	protected $_cart;

	/**
	 * @var Order
	 */
	protected $order;

	/**
	 * @var Customer
	 */
	protected $customer;

	/**
	 * @var ScopeConfigInterface
	 */
	protected $_scopeConfig;
	private $remoteAddress;
	protected $_messageManager;

	/**
	 * @param Textlocal $textlocal
	 * @param CurrentCustomer $currentCustomer
	 * @param Order $order
	 * @param Customer $customer
	 */
	public function __construct(
	CurrentCustomer $currentCustomer, Order $order, Customer $customer, \Psr\Log\LoggerInterface $logger, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\App\RequestInterface $request, RemoteAddress $remoteAddress, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\App\Response\RedirectInterface $redirect, \Magento\Framework\App\ResponseInterface $response, \Magento\Framework\App\ActionFlag $actionFlag, \Magento\Framework\App\ResponseFactory $responseFactory, \Magento\Framework\UrlInterface $url, \Magento\Checkout\Model\Cart $cart
	) {
		$this->customer = $customer;
		$this->order = $order;
		$this->_currCustomer = $currentCustomer;
		$this->_logger = $logger;
		$this->scopeConfig = $scopeConfig;
		$this->_resource = $resource;
		$this->_coreRegistry = $coreRegistry;
		$this->_request = $request;
		$this->remoteAddress = $remoteAddress;
		$this->_messageManager = $messageManager;
		$this->_redirect = $redirect;
		$this->_response = $response;
		$this->_actionFlag = $actionFlag;
		$this->_responseFactory = $responseFactory;
		$this->_url = $url;
		$this->cart = $cart;
	}

	public function execute(\Magento\Framework\Event\Observer $observer) {
		$getAllItems = $this->cart->getQuote()->getAllVisibleItems();
		$productId = array();
		foreach ($getAllItems as $item) {
			$productId[] = $item->getProductId();
		}
		$productIds = implode(',', $productId);
		$connection = $this->_resource->getConnection();
		$table_name = $this->_resource->getTableName('rptech_block_user');
		$controller = $observer->getControllerAction();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerSession = $objectManager->get('Magento\Customer\Model\Session');
		$ip = $this->remoteAddress->getRemoteAddress();
		$sql = "Select * FROM " . $table_name . " where ( user_ip_address= '" . $ip . "' ";
		$where = '';
		if ($customerSession->isLoggedIn()) {
			$user_id = $customerSession->getCustomer()->getId();
			$user_email = $customerSession->getCustomer()->getEmail();
			$where .= " OR user_id = '" . $user_id . "'";
			if (!empty($user_email)) {
				$where .= " OR user_email = '" . $user_email . "'";
			}
			if ($customerSession->getCustomer()->getPrimaryBillingAddress()) {
				$user_mobile = $customerSession->getCustomer()->getPrimaryBillingAddress()->getTelephone();
				if (!empty($user_mobile)) {
					$where .= " OR user_mobile = '" . $user_mobile . "'";
				}
			}
		}
		$sql .= $where . ' ) ';
		$sql .= " AND order_products IN(" . $productIds . ")";
		$result = $connection->fetchAll($sql);
		if (!empty($result)) {
			$flag = FALSE;
			foreach ($result as $value) {
				$purchaseStr = strtotime($value['order_datetime']);
				$cDate = strtotime(date('Y-m-d H:i:s')) - 86400;
				if ($purchaseStr > $cDate) {
					$flag = TRUE;
				}
			}
			if ($flag) {
				$msg = 'Unable to process your Request, you have already bought same Product. You can purchase the product after next 24 hours.';
				$cartUrl = $this->_url->getUrl('checkout/cart/index');
				$this->_messageManager->addErrorMessage(__($msg));
				$this->_responseFactory->create()->setRedirect($cartUrl)->sendResponse();
				exit;
			}else{
				$setFlag = FALSE;
				foreach ($productId as $product_id) {
					$product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);
					$sell_status = $product->getResource()->getAttribute('sell_status')->getFrontend()->getValue($product);
					if($sell_status == 'No'){
						$setFlag = TRUE;
					}
				}
				if($setFlag){
					$msg = 'Some products are not eligible for purchase. Please try again later.';
					$cartUrl = $this->_url->getUrl('checkout/cart/index');
					$this->_messageManager->addErrorMessage(__($msg));
					$this->_responseFactory->create()->setRedirect($cartUrl)->sendResponse();
					exit;

				}
				
			}
		} else {
			$setFlag = FALSE;
			foreach ($productId as $product_id) {
				$product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);
				$sell_status = $product->getResource()->getAttribute('sell_status')->getFrontend()->getValue($product);
				if($sell_status == 'No'){
					$setFlag = TRUE;
				}
			}
			if($setFlag){
				$msg = 'Some products are not eligible for purchase. Please try again later.';
				$cartUrl = $this->_url->getUrl('checkout/cart/index');
				$this->_messageManager->addErrorMessage(__($msg));
				$this->_responseFactory->create()->setRedirect($cartUrl)->sendResponse();
				exit;
				
			}
		}
		
	}

}
