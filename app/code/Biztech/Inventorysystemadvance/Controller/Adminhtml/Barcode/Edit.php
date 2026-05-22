<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    protected $resultPage;
    protected $layoutFactory;
    protected $barcodeModel;
    protected $registry;
    protected $backendSession;

    /**
     * @param Context                                       $context
     * @param \Magento\Framework\View\LayoutFactory         $layoutFactory
     * @param PageFactory                                   $resultPageFactory
     * @param \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel
     * @param \Magento\Framework\Registry                   $registry
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        PageFactory $resultPageFactory,
        \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->layoutFactory = $layoutFactory;
        $this->barcodeModel = $barcodeModel;
        $this->registry = $registry;
        $this->backendSession = $context->getSession();
    }

    /**
     * This function is used for edit the barcode
     * @return void
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');

        $model = $this->barcodeModel;

        $registryObject = $this->registry;

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This row no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->backendSession->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $registryObject->register('barcode_data', $model);

        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Biztech_Barcode::barcode');
        $this->resultPage->getConfig()->getTitle()->set((__('View Barcode " ' . $model->getBarcode() . ' "')));

        $editBlock = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Edit');
        $this->resultPage->addContent($editBlock);
        $this->resultPage->addLeft($this->resultPage->getLayout()->createBlock('Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Edit\Tabs'));
        return $this->resultPage;
    }
}
