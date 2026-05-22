<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class CreateBarcode extends Action
{

    protected $resultPageFactory;
    protected $resultPage;
    protected $layoutFactory;

    /**
     * @param Context                               $context
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param PageFactory                           $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * This function is used for display create barcode
     * @return object
     */
    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Biztech_Barcode::barcode');
        $this->resultPage->getConfig()->getTitle()->set((__('Create Barcodes')));

        $editBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Edit');
        $this->resultPage->addContent($editBlock);

        $childBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Productgrid\Selectproductgrid');
        $editBlock->setChild('create_barcode', $childBlock);
        return $this->resultPage;
    }
}
