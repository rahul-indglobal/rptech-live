<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierlogin;

use Magento\Framework\Session\SessionManager;

class Editaddrinfo extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $session;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param SessionManager                             $supplierSession
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        SessionManager $supplierSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $supplierSession;
        parent::__construct($context);
    }

    /**
     * This function is used for edit the supplier address information
     * @return Object
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $this->resultPage = $this->resultPageFactory->create();
            $this->resultPage->getConfig()->getTitle()->set(__('Edit Address Information'));
            return $this->resultPage;
        }
    }
}
