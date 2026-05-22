<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierproduct;

use Magento\Framework\Session\SessionManager;

class Createproduct extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $supplierSession;

    /**
     * @param \Magento\Framework\App\Action\Context      $context           [description]
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory [description]
     * @param SessionManager                             $supplierSession   [description]
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
     * This function is used for create products
     * @return Object
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $this->resultPage = $this->resultPageFactory->create();
            $this->resultPage->getConfig()->getTitle()->set(__('Create Product'));
            return $this->resultPage;
        }
    }
}
