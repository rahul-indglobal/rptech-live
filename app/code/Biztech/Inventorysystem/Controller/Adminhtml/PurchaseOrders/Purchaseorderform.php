<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Purchaseorderform extends Action
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
     * Purchase orders form
     * @return Object
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $this->resultPage = $this->resultPageFactory->create();


        if ($this->getRequest()->getParam('massaction_prepare_key') == 'pendingitems') {
            $this->resultPage->getConfig()->getTitle()->set((__('Generate PO - Items')));
        } elseif ($this->getRequest()->getParam('massaction_prepare_key') == 'pendingorders') {
            $this->resultPage->getConfig()->getTitle()->set((__('Generate Purchase Order')));
        } else {
            $this->resultPage->getConfig()->getTitle()->set((__('Generate PO')));
        }

        $editBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Purchaseorderform');
        $this->resultPage->addContent($editBlock);

        return $this->resultPage;
    }
}
