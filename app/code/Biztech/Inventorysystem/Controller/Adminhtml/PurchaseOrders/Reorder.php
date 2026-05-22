<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Reorder extends Action
{
    protected $resultPageFactory;
    protected $resultPage;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * This function used for generate reorder of the PO
     * @return Object
     */
    public function execute()
    {

        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Biztech_Inventorysystem::purchaseorders');
        $this->resultPage->getConfig()->getTitle()->prepend((__('Purchase Orders')));
        return $this->resultPage;
    }
}
