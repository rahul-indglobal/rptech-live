<?php

namespace Lalita\Mysmsmodule\Controller\Index;

use Magento\Framework\App\RequestInterface;

class Enquiry extends \Magento\Framework\App\Action\Action {

	const XML_PATH_EMAIL_RECIPIENT = 'trans_email/ident_general/email';

	protected $inlineTranslation;
	protected $scopeConfig;
	protected $_escaper;

	/**
	 * @var \Magento\Framework\Controller\Result\JsonFactory
	 */
	protected $resultJsonFactory;

	/**
	 * @var \Magento\Framework\App\Request\Http
	 */
	protected $_request;

	/**
	 * @var \Magento\Framework\Mail\Template\TransportBuilder
	 */
	protected $_transportBuilder;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	/**
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 */
	public function __construct(
	\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\App\Request\Http $request
	, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
	, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Escaper $escaper) {
		$this->resultJsonFactory = $resultJsonFactory;
		$this->_resource = $resource;
		$this->_request = $request;
		$this->_transportBuilder = $transportBuilder;
		$this->_storeManager = $storeManager;
		$this->inlineTranslation = $inlineTranslation;
		$this->scopeConfig = $scopeConfig;
		$this->_escaper = $escaper;
		parent::__construct($context);
	}

	/**
	 * View  page action
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */
	public function execute() {
		$result = $this->resultJsonFactory->create();
		$connection = $this->_resource->getConnection();
		$table_name = $this->_resource->getTableName('rptech_enquiry_form');
		if ($this->getRequest()->isAjax() && $this->getRequest()->isPost()) {
			$post = $this->getRequest()->getPostValue();
			$connection->query("INSERT INTO `" . $table_name . "` (`username`,`useremail`,`sku`,`productname`,`comments`)VALUES 
				('" . $post['username'] . "','" . $post['useremail'] . "','" . $post['product_sku'] . "','" . $post['product_name'] . "','" . $post['usercomment'] . "')");

			$this->inlineTranslation->suspend();
			try {
				$error = false;
				$sender = [
				    'name' => $this->_escaper->escapeHtml($post['username']),
				    'email' => $this->_escaper->escapeHtml($post['useremail']),
				    'sku' => $this->_escaper->escapeHtml($post['product_sku']),
				    'product_name' => $this->_escaper->escapeHtml($post['product_name']),
				    'usercomment' => $this->_escaper->escapeHtml($post['usercomment']),
				];
				$postObject = new \Magento\Framework\DataObject();
				$postObject->setData($sender);
				$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
				$transport = $this->_transportBuilder
					->setTemplateIdentifier('1') // Send the ID of Email template which is created in Admin panel
					->setTemplateOptions(
						[
						    'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
						    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
						]
					)
					->setTemplateVars(['data' => $postObject])
					->setFrom($sender)
					->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
					->getTransport();
				$transport->sendMessage();
				$this->inlineTranslation->resume();
			} catch (\Exception $e) {
				\Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->debug($e->getMessage());
			}
			$data = ['message' => 'Success'];
		} else {
			$data = ['message' => 'Fail'];
		}

		return $result->setData($data);
	}

}
