<?php
    namespace Lalita\Mysmsmodule\Controller\Adminhtml\Enquiry;
    
    use Magento\Backend\App\Action;

    class Index extends \Magento\Backend\App\Action
    {
        public function __construct(
            Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory
        )
        {
            $this->resultPageFactory = $resultPageFactory;
            parent::__construct($context);
        }
        
        public function execute()
        {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend((__('Enquiries')));
//            die('JKL');
            return $resultPage;
        }
    }