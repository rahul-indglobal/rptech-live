<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class AftersavePO extends Action
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
     * Save after po generate
     * @return Object
     */
    public function execute()
    {

        $this->resultPage = $this->resultPageFactory->create();
        
        $this->resultPage->getConfig()->getTitle()->prepend((__('Review Purchase Orders')));

        $editBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Create\Afterpurchaseordersave');
        $this->resultPage->addContent($editBlock);

        return $this->resultPage;
    }
}
