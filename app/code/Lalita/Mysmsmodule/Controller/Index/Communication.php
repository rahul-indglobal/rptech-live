<?php

namespace Lalita\Mysmsmodule\Controller\Index;

use Magento\Framework\App\RequestInterface;

class Communication extends \Magento\Framework\App\Action\Action
{
	const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_general/email';
	const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_general/name';

	protected $inlineTranslation;
	protected $scopeConfig;
	protected $_escaper;
	protected $resultJsonFactory;
	protected $_request;
	protected $_transportBuilder;
	protected $_storeManager;
	protected $enquiryFactory;
    protected $session;
    protected $customerRepository;
    protected $rptHelper;

	public function __construct(
	\Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
	\Magento\Customer\Model\Session $session,
	\Magento\Framework\App\Action\Context $context,
	\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
	\Magento\Framework\App\ResourceConnection $resource,
	\Magento\Framework\App\Request\Http $request,
	\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
	\Magento\Store\Model\StoreManagerInterface $storeManager,
	\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
	\Magento\Framework\Escaper $escaper,
	\Lalita\Mysmsmodule\Model\EnquiryFactory $enquiryFactory,
	\Rptech\Communication\Helper\Data $rptHelper) {
		$this->customerRepository = $customerRepository;
		$this->session = $session;
		$this->resultJsonFactory = $resultJsonFactory;
		//$this->_resource = $resource;
		//$this->_request = $request;
		$this->_transportBuilder = $transportBuilder;
		$this->_storeManager = $storeManager;
		$this->inlineTranslation = $inlineTranslation;
		$this->scopeConfig = $scopeConfig;
		$this->_escaper = $escaper;
		//$this->enquiryFactory = $enquiryFactory;
		$this->rptHelper = $rptHelper;
		parent::__construct($context);
	}

	public function execute() {
		$logger = \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface');
		$result = $this->resultJsonFactory->create();

		$storeId = $this->_storeManager->getStore()->getId();
		$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

		if ($this->getRequest()->isAjax() && $this->getRequest()->isPost()) {
			$post = $this->getRequest()->getPostValue();

			$number = $this->_escaper->escapeHtml($post['usermobile']);
            if(!empty($number)) {
            	$logger->info("Sending WhatsApp message of Send Enquiry to ".$number);
                if(strlen($number)==12 || strlen($number)>10) {
                    if(substr($number, 0, 2)=="91") {
                        $number = "+".$number;
                    }
                } else if(strlen($number)<=10) {
                	$number = "+91".$number;
                }

                $price = isset($post['product_price']) ? $this->_escaper->escapeHtml($post['product_price']) : 0;
                $resp = $this->rptHelper->sendSendEnuiryWhatsAppMessageToCustomer($number,
	                				$this->_escaper->escapeHtml($post['username']),
	                				$this->_escaper->escapeHtml($post['product_name']),
	                				$this->_escaper->escapeHtml($post['product_sku']),
	                				$price, $this->_escaper->escapeHtml($post['product_image']),
	                				$this->_escaper->escapeHtml($post['product_url']));
                $resp = json_decode($resp);
				if(!is_object($resp) || (is_object($resp) && $resp->status!="processing")) {
					$logger->info("Some error sending WhatsApp message of Send Enquiry");
					$logger->info(print_r($resp,1));
				} else {
					$logger->info("WhatsApp message of Send Enquiry sent Successfully");
				}
			}

			/* E-MAIL CODE */
			$this->inlineTranslation->suspend();
			try {
				/* EMAIL TO ADMIN */
				$sender = [
				    'name' => $this->_escaper->escapeHtml($post['username']),
				    'email' => $this->_escaper->escapeHtml($post['useremail'])
				];
				$postData = [
				    'name' => $this->_escaper->escapeHtml($post['username']),
				    'email' => $this->_escaper->escapeHtml($post['useremail']),
				    'sku' => $this->_escaper->escapeHtml($post['product_sku']),
				    'product_name' => $this->_escaper->escapeHtml($post['product_name']),
				    'product_url' => $this->_escaper->escapeHtml($post['product_url']),
				    'usermobile' => $this->_escaper->escapeHtml($post['usermobile']),
				    'usercity' => $this->_escaper->escapeHtml($post['usercity']),
				    'usercomment' => $this->_escaper->escapeHtml($post['usercomment']),
				    'lid' => isset($post['lid']) ? $this->_escaper->escapeHtml($post['lid']) : ""
				];
				$postObject = new \Magento\Framework\DataObject();
				$postObject->setData($postData);
				$transport = $this->_transportBuilder
					->setTemplateIdentifier(1) // Set the ID of Email template which is created in Admin panel
					->setTemplateOptions(
						[
						    'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
						    'store' => $storeId,
						]
					)
					->setTemplateVars(['data' => $postObject])
					->setFrom($sender)
					->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT_EMAIL, $storeScope))
					->getTransport();
				$transport->sendMessage();

				/* EMAIL TO CUSTOMER */
				$sender = [
				    'name' => $this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT_NAME, $storeScope),
				    'email' => $this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT_EMAIL, $storeScope)
				];
				$postData = [
				    'name' => $this->_escaper->escapeHtml($post['username']),
				    'sku' => $this->_escaper->escapeHtml($post['product_sku']),
				    'product_name' => $this->_escaper->escapeHtml($post['product_name']),
				    'usermobile' => $number,
				    'product_image' => $this->_escaper->escapeHtml($post['product_image']),
				    'product_url' => $this->_escaper->escapeHtml($post['product_url']),
				    'product_price' => $this->_escaper->escapeHtml($post['product_price']),
				    'lid' => isset($post['lid']) ? $this->_escaper->escapeHtml($post['lid']) : ""
				];
				$postObject = new \Magento\Framework\DataObject();
				$postObject->setData($postData);
				$transport = $this->_transportBuilder
					->setTemplateIdentifier(4)
					->setTemplateOptions(
						[
						    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
						    'store' => $storeId,
						]
					)
					->setTemplateVars(['data' => $postObject])
					->setFrom($sender)
					->addTo($this->_escaper->escapeHtml($post['useremail']))
					->getTransport();
				$transport->sendMessage();
			} catch (\Exception $e) {
				$logger->info("Send Enquiry Exception Cought");
				$logger->info($e->getMessage());
				$data = ['message' => $e->getMessage()];
			}
			$this->inlineTranslation->resume();

			$data = ['message' => 'Success'];
		}
		return $result->setData($data);
	}
}