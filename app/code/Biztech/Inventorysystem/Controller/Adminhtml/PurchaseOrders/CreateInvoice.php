<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Biztech\Inventorysystem\Model\Purchaseorders;

class CreateInvoice extends Action
{
    protected $resultPageFactory;
    protected $_purchaseOrders;
    protected $resultPage;

    /**
     * @param Context        $context
     * @param PageFactory    $resultPageFactory
     * @param Purchaseorders $purchaseOrders
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Purchaseorders $purchaseOrders
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_purchaseOrders = $purchaseOrders;
    }

    /**
     * Create purchase invoice
     * @return Object
     */
    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $poID = $this->getRequest()->getParam('porder_id');
        $po = $this->_purchaseOrders->load($poID);
        $title = 'PO Invoice' . ' | ' . $po->getPurchaseOrderId();
        $this->resultPage->getConfig()->getTitle()->prepend($title);
        return $this->resultPage;
    }
}
