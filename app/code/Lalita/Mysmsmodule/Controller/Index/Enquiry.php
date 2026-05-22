<?php

namespace Lalita\Mysmsmodule\Controller\Index;

use Magento\Framework\App\RequestInterface;

class Enquiry extends \Magento\Framework\App\Action\Action {

	const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_general/email';
	const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_general/name';

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

	protected $enquiryFactory;

	/**
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 */
	public function __construct(
	\Magento\Framework\App\Action\Context $context,
	\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
	\Magento\Framework\App\ResourceConnection $resource,
	\Magento\Framework\App\Request\Http $request,
	\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
	\Magento\Store\Model\StoreManagerInterface $storeManager,
	\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
	\Magento\Framework\Escaper $escaper,
	\Lalita\Mysmsmodule\Model\EnquiryFactory $enquiryFactory) {
		$this->resultJsonFactory = $resultJsonFactory;
		$this->_resource = $resource;
		$this->_request = $request;
		$this->_transportBuilder = $transportBuilder;
		$this->_storeManager = $storeManager;
		$this->inlineTranslation = $inlineTranslation;
		$this->scopeConfig = $scopeConfig;
		$this->_escaper = $escaper;
		$this->enquiryFactory = $enquiryFactory;
		parent::__construct($context);
	}
    
    /**
     * @throws \Magento\Framework\Exception\MailException
     */
	public function execute() {
        try {
            date_default_timezone_set('Asia/Kolkata');
            $curr_date_time = date('Y-m-d H:i:s');
            //echo "<br>Curr Time: ".$curr_date_time;
            $date1Timestamp = strtotime($curr_date_time);
            $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface');
            
            //$connection = $this->_resource->getConnection();
            //$table_name = $this->_resource->getTableName('rptech_enquiry_form');
            if ($this->getRequest()->isAjax() && $this->getRequest()->isPost()) {
                $post = $this->getRequest()->getPostValue();
                /*$connection->query("INSERT INTO `" . $table_name . "` (`username`,`useremail`,`sku`,`productname`,`mobile`,`city`,`comments`)VALUES
                    ('" . $post['username'] . "','" . $post['useremail'] . "','" . $post['product_sku'] . "','" . $post['product_name'] . "','" . $post['usermobile'] . "','" . $post['usercity'] . "','" . $post['usercomment'] . "')");*/
                $model = $this->enquiryFactory->create();
                $coll = $model->getCollection()->addFieldToFilter('useremail',["like"=>$post['useremail']])
                    ->addFieldToFilter('sku',$post['product_sku'])->setOrder('id','desc');
                //echo $coll->getSelect()->__toString();
                $nor = $coll->getSize();
                //echo "<br>NOR: ".$nor;
                $flag = 0;
        
                if($nor >=1) {
                    //echo "...".$coll->getData()[0]['id']. "__" . $coll->getData()[0]['date'];
                    $prev_date_time = $coll->getData()[0]['date'];
                    $date2Timestamp = strtotime($prev_date_time);
                    $difference = $date1Timestamp - $date2Timestamp;
                    $hours = $difference / 60 / 60 ;
                    if($hours < 24) {
                        $flag = 1;
                        $data = ['message' => 'Cancel'];
                    }
                }
                if($nor==0 || $flag==0) {
                    $model->setData(['username'=> $post['username'],
                                     'useremail'=> $post['useremail'],
                                     'sku'=> $post['product_sku'],
                                     'productname'=> $post['product_name'],
                                     'mobile'=> $post['usermobile'],
                                     'city'=> $post['usercity'],
                                     'comments'=> $post['usercomment'],
                                     'user_type'=> isset($post['liketoknow']) ? $post['liketoknow'] : null,
                                     'where_parts_will_be_used'=> isset($post['applicationnvidia']) ? $post['applicationnvidia'] : null,
                                     'quantity'=> isset($post['quantity']) ? $post['quantity'] : null,
                                     'company_name'=> isset($post['company_name']) ? $post['company_name']:null,
                                    ]);
                    $result = $model->save();
                    if($result){
                        $templateVar = [
                            'username' => $post['username'],
                            'useremail' => $post['useremail'],
                            'usermobile' => $post['usermobile'],
                            'usercity' => $post['usercity'],
                            'usercomment' => $post['usercomment'],
                            'product_sku' => $post['product_sku'],
                            'product_name' => $post['product_name'],
	                        'liketoknow'=> isset($post['liketoknow']) ? $post['liketoknow'] : null,
	                        'applicationnvidia'=> isset($post['applicationnvidia']) ? $post['applicationnvidia'] : null,
	                        'company_name'=> isset($post['company_name']) ? $post['company_name']:null,
	                        'quantity'=> isset($post['quantity']) ? $post['quantity'] : null
                        ];
                        $this->sendMailNotification($templateVar);
                    }
                    $lastInsrtedId = $model->getId();
                    //$logger->info("Record saved with ID - ".$lastInsrtedId);
                    $data = ['message' => 'Success', 'lid' => $lastInsrtedId];
                }
            } else {
                $data = ['message' => 'Fail'];
            }
            $result = $this->resultJsonFactory->create();
            return $result->setData($data);
        }catch (\Exception $ex){
            return $ex->getTraceAsString();
        }
		
	}
    
    /**
     * @param $templateVar
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMailNotification($templateVar){
        $supportName = $this->scopeConfig->getValue('trans_email/ident_support/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $supportEmail = $this->scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $recepientName = $this->scopeConfig->getValue('rpt_general/enquiry_email/recepient_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $recepientEmail = $this->scopeConfig->getValue('rpt_general/enquiry_email/recepient_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier('enquiry_email_template')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
            ->setTemplateVars($templateVar) // Pass necessary data to template
            ->setFrom(['email' => $supportEmail, 'name' => $supportName])
            ->addTo($recepientEmail, $recepientName)
            ->getTransport();
        $transport->sendMessage();
    }
}
