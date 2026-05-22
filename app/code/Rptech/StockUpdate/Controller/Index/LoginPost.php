<?php

namespace Rptech\StockUpdate\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class LoginPost extends \Magento\Framework\App\Action\Action
{
    private $customerAccountManagement;
    private $helperdata;
    private $logger;
    private $session;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Magento\Customer\Model\Session $session,
        \Magecomp\Mobilelogin\Helper\Data $helper)
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
        $this->session = $session;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->helperdata = $helper;
    }
    
    public function execute() {
        $data = "false";
        $mobile = $this->getRequest()->get('mobile');
        $otp = $this->getRequest()->get('otp');
        $isExist = $this->helperdata->checkLoginOTPCode($mobile, $otp);
        if ($isExist == 1) {
            $this->session->setStockUpdateAdmin(true);
            $this->session->regenerateId();
            $data = "true";
        }
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($data);
        return $resultJson;
    }
}