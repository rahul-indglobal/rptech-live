<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

class MassStatus extends \Magento\Backend\App\Action
{
    protected $_purchaseorderModel;

    /**
     * @param \Magento\Backend\App\Action\Context           $context
     * @param \Biztech\Inventorysystem\Model\Purchaseorders $purchaseorderModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Biztech\Inventorysystem\Model\Purchaseorders $purchaseorderModel
    ) {

        $this->_purchaseorderModel = $purchaseorderModel;
        parent::__construct($context);
    }

    /**
     * Update multiple statuses for the PO
     * @return Void
     */
    public function execute()
    {

        $ids = $this->getRequest()->getParam('purchaseorders');
        $selectedStatus = $this->getRequest()->getParam('status');

        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select Purchase Order(s) to change status.'));
        } else {
            try {
                $notUpdateFlag = 0;
                $updateFlag = 0;
                foreach ($ids as $id) {
                    $row = $this->_purchaseorderModel->load($id);
                    $currentStatus = $row->getStatus();

                    if (($selectedStatus == 'pending' || $selectedStatus == "processing") && ($currentStatus == 'completed' || $currentStatus == 'canceled')) {
                        $notUpdateFlag++;
                        continue;
                    } else if ($selectedStatus == 'completed' && $currentStatus == 'canceled') {
                        $notUpdateFlag++;
                        continue;
                    } else if ($selectedStatus == 'canceled' && ($currentStatus == 'completed' || $currentStatus == 'processing')) {
                        $notUpdateFlag++;
                        continue;
                    }
                    $row->setStatus($selectedStatus);
                    $row->save();
                    $updateFlag++;
                }
                if ($updateFlag > 0) {
                    $this->messageManager->addSuccess(__('Total of %1 record(s) were successfully updated.', $updateFlag));
                }
                if ($notUpdateFlag > 0) {
                    if ($selectedStatus == 'pending' || $selectedStatus == 'processing') {
                        $message = "Total of %1 record(s) were not updated successfully as they were already Completed OR Canceled.";
                    } else if ($selectedStatus == 'completed') {
                        $message = "Total of %1 record(s) were not updated successfully as they were already Canceled.";
                    } else if ($selectedStatus == 'canceled') {
                        $message = "Total of %1 record(s) were not updated successfully as they were already Completed OR Processing.";
                    }

                    $this->messageManager->addError(__($message, $notUpdateFlag));
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
