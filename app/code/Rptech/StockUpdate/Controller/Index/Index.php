<?php

namespace Rptech\StockUpdate\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    private $logger;
    private $session;
    
    public function __construct(
            \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            \Psr\Log\LoggerInterface $logger,
            \Magento\Customer\Model\Session $session)
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
        $this->session = $session;
    }
    
    public function execute() {
        if ($this->session->isLoggedIn() && !$this->session->getStockUpdateAdmin()) {
            $this->session->logout();
        }
        if ($this->session->getStockUpdateAdmin() && $this->session->getStockUpdateAdmin()==true) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('productstock/index/manage');
            return $resultRedirect;
        }
        $title = __("Stock Admin Login");
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($title);
        return $resultPage;
    }
}