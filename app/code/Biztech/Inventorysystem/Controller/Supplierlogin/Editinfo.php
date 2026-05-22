<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierlogin;

use Magento\Framework\Session\SessionManager;

class Editinfo extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context      $context           [description]
     * @param SessionManager                             $supplierSession   [description]
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory [description]
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        SessionManager $supplierSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->session = $supplierSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * This function is used for edit supplier information
     * @return Object
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $this->resultPage = $this->resultPageFactory->create();
            $this->resultPage->getConfig()->getTitle()->set(__('Edit Account Information'));
            return $this->resultPage;
        }
    }
}
