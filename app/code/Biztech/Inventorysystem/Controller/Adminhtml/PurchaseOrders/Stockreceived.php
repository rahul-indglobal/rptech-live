<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Stockreceived extends Action
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
     * This function is used for showing stock received
     * @return Object
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $this->resultPage = $this->resultPageFactory->create();

        $this->resultPage->getConfig()->getTitle()->set((__('Stock Received')));

        $editBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Stockreceived');
        $this->resultPage->addContent($editBlock);

        return $this->resultPage;
    }
}
