<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierproduct;

use Magento\Framework\Session\SessionManager;

class Viewproduct extends \Magento\Framework\App\Action\Action
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
        $this->resultPageFactory = $resultPageFactory;
         $this->session = $supplierSession;
        parent::__construct($context);
    }

    /**
     * This function is used for view products
     * @return Object
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $this->resultPage = $this->resultPageFactory->create();
            $this->resultPage->getConfig()->getTitle()->set(__('Supplier Product'));
            return $this->resultPage;
        }
    }
}
