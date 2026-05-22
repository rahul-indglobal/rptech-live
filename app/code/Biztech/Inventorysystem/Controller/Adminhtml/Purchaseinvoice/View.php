<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Purchaseinvoice;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends Action
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
     * View purchase invoice
     * @return Object
     */
    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $editBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Purchaseinvoice\Edit\Tab\View');
        $this->resultPage->addContent($editBlock);
        $this->resultPage->setActiveMenu('Biztech_Inventorysystem::purchaseinvoice');
        $this->resultPage->getConfig()->getTitle()->prepend('Purchase Invoice');
        return $this->resultPage;
    }
}
