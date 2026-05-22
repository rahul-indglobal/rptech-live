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

class BeforeCompareAdd implements ObserverInterface {

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
	\Magento\Catalog\Model\Product\Compare\ListCompare $listCompare,\Magento\Framework\UrlInterface $url, \Magento\Catalog\CustomerData\CompareProducts $compareProducts, CurrentCustomer $currentCustomer, Order $order, Customer $customer, \Psr\Log\LoggerInterface $logger, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\App\RequestInterface $request, RemoteAddress $remoteAddress, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\App\Response\RedirectInterface $redirect, \Magento\Framework\App\ResponseInterface $response,\Magento\Framework\App\ResponseFactory $responseFactory, \Magento\Framework\App\ActionFlag $actionFlag
	) {
		$this->listCompare = $listCompare;
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
		$this->compareProducts = $compareProducts;
		$this->_responseFactory = $responseFactory;
		$this->_url = $url;
	}

	public function execute(\Magento\Framework\Event\Observer $observer) {
		echo '<pre>';

		$products = $this->compareProducts->getSectionData();
//		print_r($products);
		$product = $observer->getEvent()->getProduct();
		$key = array_search($product->getId(), array_column($products['items'], 'id'));
		$cnt = $products['count'];
//		echo '--<br>';
		$cntItem = count($products['items']);
//		echo '--<br>';
//		die;
		$controller = $observer->getControllerAction();
		if ($cnt == $cntItem  && ($cnt !=0 || $cntItem !=0)) {
			$url = $this->_url->getUrl('catalog/product_compare');
			$msg = $product->getName().' is already in <a href="'.$url.'">compare list</a>.';
//			$cartUrl = $this->_url->getUrl('checkout/cart/index');
			$messageCollection = $this->_messageManager->getMessages(true);
//			print_r($messageCollection->getLastAddedMessage()->getText());
			$this->_messageManager->addError(__($msg));
			$this->_request->setParam('product', false); // Will not add product to Cart
			$this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
			$this->_responseFactory->create()->setRedirect($this->_redirect->getRefererUrl())->sendResponse();
			exit;
			
//			$this->_messageManager->addError(__('Order status change successfully')); // You can set your success message here
//			$this->_redirect->redirect($controller->getResponse(), $this->_redirect->getRefererUrl()); // You can set here on which path you want to redirect
//			throw new \Magento\Framework\Exception\CouldNotDeleteException(__("Prices have been changed!"));
//			return $this->_messageManager->addError('Product already added in compare list.');
//			exit;
//			throw new \Magento\Framework\Exception\LocalizedException(__('Product already added in compare list.'));
		}
//		die('here');
	}

}
