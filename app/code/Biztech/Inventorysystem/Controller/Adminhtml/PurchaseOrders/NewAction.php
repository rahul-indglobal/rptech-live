<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class NewAction extends Action
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
     * New Action PO
     * @return Object
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $this->resultPage = $this->resultPageFactory->create();

        $this->resultPage->getConfig()->getTitle()->set((__('Create Purchase Order')));

        $editBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Edit');
        $this->resultPage->addContent($editBlock);


        $childBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Create\Purchaseordercreate');
        $editBlock->setChild('purchase_order_create_grid', $childBlock);
        return $this->resultPage;
    }
}
