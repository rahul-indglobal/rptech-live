<?php


namespace Brainvire\Productdetail\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;

class Addcontactus extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;
 
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory, JsonFactory $resultJsonFactory)
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
 
        parent::__construct($context);
    }
	
    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
		$producturl = $this->getRequest()->getParam('producturl');
        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
       
        $block = $resultPage->getLayout()
                ->createBlock('Magento\Contact\Block\ContactForm')
				->setTemplate('Brainvire_Productdetail::contact_form.phtml')
				->setProducturl($producturl)
                ->toHtml();
 
        $result->setData(['html' => $block]);
        return $result;
    }
}
