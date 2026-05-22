<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierorder;

use Magento\Framework\Session\SessionManager;

class Vieworderdetails extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $supplierSession;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param SessionManager                             $supplierSession
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        SessionManager $supplierSession
    ) {
        $this->session = $supplierSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * This function is used for view order details
     * @return Object
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $this->resultPage = $this->resultPageFactory->create();
            $this->resultPage->getConfig()->getTitle()->set(__('View Purchase Order Detail'));
            return $this->resultPage;
        }
    }
}
