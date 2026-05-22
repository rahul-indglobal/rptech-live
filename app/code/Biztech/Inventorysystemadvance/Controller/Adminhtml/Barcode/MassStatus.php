<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Backend\App\Action\Context;

class MassStatus extends \Magento\Backend\App\Action
{

    protected $barcodeModel;

    /**
     * @param Context                                       $context      [description]
     * @param \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel [description]
     */
    public function __construct(
        Context $context,
        \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel
    ) {
        parent::__construct($context);
        $this->barcodeModel = $barcodeModel;
    }
    
    /**
     * This function is used for update the multiple statuses
     * @return void
     */
    public function execute()
    {
        $ids = $this->getRequest()->getParam('barcodes');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->barcodeModel->load($id);
                    $row->setData('status', $status)
                            ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) were successfully updated.', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
