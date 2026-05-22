<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Backend\App\Action\Context;

class MassDelete extends \Magento\Backend\App\Action
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
     * This function is used for the remove multiple barcodes
     * @return void
     */
    public function execute()
    {
        
         $ids = $this->getRequest()->getParam('id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select product(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->barcodeModel->load($id);
                    $row->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
         $this->_redirect('*/*/');
    }
}
