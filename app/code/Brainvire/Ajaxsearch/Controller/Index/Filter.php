<?php
namespace Brainvire\Ajaxsearch\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class Filter extends Action {
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;
    protected $categoryRepository;

    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory, JsonFactory $resultJsonFactory, \Magento\Framework\Registry $registry,CategoryRepositoryInterface $categoryRepository)
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->registry = $registry;
        $this->categoryRepository = $categoryRepository;
 
        parent::__construct($context);
    }

    public function execute() {

        $query = $this->getRequest()->getParam('query');
        
        $category = $this->categoryRepository->get(375);

        $this->registry->unregister('category');
        $this->registry->unregister('current_category');
        $this->registry->register('category', $category);
        $this->registry->register('current_category', $category);

        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
       
        $block = $resultPage->getLayout()
                ->createBlock('Brainvire\Ajaxsearch\Block\ListProduct')
                ->setTemplate('Brainvire_Ajaxsearch::list.phtml')
                ->setQueryText($query)
                ->toHtml();
 
        $result->setData(['html' => $block]); 
		//echo "<pre>";print_r($result);exit;
        return $result; 
    }
    
}