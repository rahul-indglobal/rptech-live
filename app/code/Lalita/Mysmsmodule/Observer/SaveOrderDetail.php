<?php

namespace Lalita\Mysmsmodule\Observer;

use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Sales\Model\Order;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SaveOrderDetail implements ObserverInterface {

	protected $_logger;
	protected $scopeConfig;

	/**
	 * @var CurrentCustomer
	 */
	protected $_currCustomer;

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

	/**
	 * @param Textlocal $textlocal
	 * @param CurrentCustomer $currentCustomer
	 * @param Order $order
	 * @param Customer $customer
	 */
	public function __construct(
	CurrentCustomer $currentCustomer, Order $order, Customer $customer, \Psr\Log\LoggerInterface $logger, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\RequestInterface $request
	) {
		$this->customer = $customer;
		$this->order = $order;
		$this->_currCustomer = $currentCustomer;
		$this->_logger = $logger;
		$this->scopeConfig = $scopeConfig;
		$this->_resource = $resource;
		$this->_coreRegistry = $coreRegistry;
		$this->_request = $request;
	}

	/**
	 * Handler for 'customer_logout' event.
	 *
	 * @param  Observer $observer
	 */
	public function execute(\Magento\Framework\Event\Observer $observer) {
		$connection = $this->_resource->getConnection();
		$table_name = $this->_resource->getTableName('rptech_block_user');
		$event = $observer->getEvent();
		$orderId = $observer->getEvent()->getOrderIds(); // $order->getId();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($orderId[0]);
		$customerId = $order->getCustomerId();
		$customer = $this->customer->load($order->getCustomerId());
		$getAllItems = $order->getAllVisibleItems();
		$createdAt = $order->getCreatedAt();
		$user_ip = $order->getRemoteIp();
		$addressObj = $order->getShippingAddress()->getData();
		$phoneNumber = $addressObj['telephone'];
		$email = $addressObj['email'];
		foreach ($getAllItems as $item) {
			$productId = $item->getProductId();
			$connection->query("INSERT INTO " . $table_name . " (`user_id`,`user_ip_address`,`user_email`,`user_order_id`,`user_mobile`,`order_datetime`,`order_products`)VALUES (
			'" . $customerId . "','" . $user_ip . "','" . $email . "','" . $orderId[0] . "','" . $phoneNumber . "','" . $createdAt . "','" . $productId . "')");
		}
		return $this;
	}

}
