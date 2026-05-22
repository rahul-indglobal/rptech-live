<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class ScanBarcode extends Action
{

    protected $resultPageFactory;
    protected $layoutFactory;
    protected $JsonFactory;

    /**
     * @param Context                               $context           [description]
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory     [description]
     * @param PageFactory                           $resultPageFactory [description]
     * @param JsonFactory                           $JsonFactory       [description]
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        PageFactory $resultPageFactory,
        JsonFactory $JsonFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->layoutFactory = $layoutFactory;
        $this->JsonFactory = $JsonFactory;
    }

    /**
     * This function is used for the scan the barcode
     * @return object
     */
    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Biztech_Barcode::barcode');
        $this->resultPage->getConfig()->getTitle()->set((__('Scan Barcode')));
        
        if ($this->getRequest()->getParam('barcode')) {
            $resultJson = $this->JsonFactory->create();
            $block = $this->_view->getLayout()->createBlock('Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Edit\Tab\Scan');
            $block->setTemplate('Biztech_Inventorysystemadvance::inventorysystemadvance/barcode/viewbarcode.phtml');
            $data = $block->toHtml();
            $resultJson->setData($data);
            return $resultJson;
        } else {
            $editBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Edit\Tab\Scan');
            $this->resultPage->addContent($editBlock);
            return $this->resultPage;
        }
    }
}
