<?php

namespace Rptech\StockUpdate\Controller\Index;

class SignOut extends \Magento\Framework\App\Action\Action
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
        $this->session->unsStockUpdateAdmin();
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('productstock/index/index');
        return $resultRedirect;
    }
}