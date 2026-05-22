<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Backend\App\Action\Context;

class Delete extends \Magento\Backend\App\Action
{
    protected $barcodeModel;

    /**
     * @param Context                                       $context
     * @param \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel
     */
    public function __construct(
        Context $context,
        \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel
    ) {
        parent::__construct($context);
        $this->barcodeModel = $barcodeModel;
    }
    
    /**
     * This function is used for the remove the barcode.
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        try {
                $banner = $this->barcodeModel->load($id);
                $banner->delete();
                $this->messageManager->addSuccess(
                    __('Delete successfully !')
                );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
