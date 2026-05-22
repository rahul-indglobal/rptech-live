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

class BeforeAddToCart implements ObserverInterface {

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
	CurrentCustomer $currentCustomer, Order $order, Customer $customer, \Psr\Log\LoggerInterface $logger, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\App\RequestInterface $request, RemoteAddress $remoteAddress, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\App\Response\RedirectInterface $redirect, \Magento\Framework\App\ResponseInterface $response, \Magento\Framework\App\ActionFlag $actionFlag
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
	}

	public function execute(\Magento\Framework\Event\Observer $observer) {
		$connection = $this->_resource->getConnection();
		$table_name = $this->_resource->getTableName('rptech_block_user');
		$controller = $observer->getControllerAction();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerSession = $objectManager->get('Magento\Customer\Model\Session');
		$ip = $this->remoteAddress->getRemoteAddress();
		$item = $observer->getQuoteItem();
		$item = ( $item->getParentItem() ? $item->getParentItem() : $item );
		$id = $item->getProductId();
		$display_text = $this->scopeConfig->getValue('cartban/general/display_text', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$hours_limit = $this->scopeConfig->getValue('cartban/general/hours_limit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$display_text_order = $this->scopeConfig->getValue('cartban/general/display_text_order', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

		$_id = $customerSession->getCustomer()->getId();

		$sql = "Select * FROM " . $table_name . " where ( user_id= '" . $ip . "' ";
		$where = '';
		$product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
		$b2bproducttype = $product->getData('b2bproducttype');
		if ($customerSession->isLoggedIn()) {
			$groupId = $customerSession->getCustomer()->getGroupId();
			if ($groupId == '4' && $b2bproducttype) {

				$b2bproductprice = $product->getData('b2bproductprice');
				$item->setCustomPrice($b2bproductprice);
				$item->setOriginalCustomPrice($b2bproductprice);
				$item->getProduct()->setIsSuperMode(true);
			}
//			die('here');
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
		$sql .= " AND order_products='" . $id . "'";
//		echo $sql;die;
		$result = $connection->fetchAll($sql);
		if (!empty($result)) {
			$flag = FALSE;
			foreach ($result as $value) {
				$purchaseStr = strtotime($value['order_datetime']);
				$cDate = strtotime(date('Y-m-d H:i:s')) - ($hours_limit*3600);
				if ($purchaseStr > $cDate) {
					$flag = TRUE;
				}
			}
			if ($flag) {
				$this->_request->setParam('product', false); // Will not add product to Cart
				$this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
				throw new \Magento\Framework\Exception\LocalizedException(__($display_text));
			}
		}
		return $this;
	}

}
