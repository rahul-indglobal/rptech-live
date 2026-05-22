<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Purchaseinvoice;

class MassStatus extends \Magento\Backend\App\Action
{

    protected $_purchaseinvoiceModel;

    /**
     * @param \Magento\Backend\App\Action\Context            $context
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoice $purchaseinvoiceModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Biztech\Inventorysystem\Model\Purchaseinvoice $purchaseinvoiceModel
    ) {

        $this->_purchaseinvoiceModel = $purchaseinvoiceModel;
        parent::__construct($context);
    }

    /**
     * This function is used for update multiple status for purchase invoice
     * @return Void
     */
    public function execute()
    {
        $poInvIds = $this->getRequest()->getParam('id');
        if (!is_array($poInvIds)) {
            $this->messageManager->addError(__('Please select item(s)'));
        } else {
            try {
                $notUpdateFlag = 0;
                $updateFlag = 0;
                foreach ($poInvIds as $poInvId) {
                    $selectedStatus = $this->getRequest()->getParam('invoice_status');
                    $currentStatus = $this->_purchaseinvoiceModel->load($poInvId)->getInvoiceStatus();
                    if ($selectedStatus == 'pending' && ($currentStatus == 'paid' || $currentStatus == 'cancel')) {
                        $notUpdateFlag++;
                        continue;
                    } else if ($selectedStatus == 'cancel' && $currentStatus == 'paid') {
                        $notUpdateFlag++;
                        continue;
                    } else if ($selectedStatus == 'paid' && $currentStatus == 'cancel') {
                        $notUpdateFlag++;
                        continue;
                    }
                    $inventorysystem = $this->_purchaseinvoiceModel->load($poInvId)
                            ->setInvoiceStatus($selectedStatus)
                            ->setIsMassupdate(true)
                            ->save();
                    $updateFlag++;
                }
                if ($updateFlag > 0) {
                    $this->messageManager->addSuccess(__('Total of %1 record(s) were updated successfully.', $updateFlag));
                }
                if ($notUpdateFlag > 0) {
                    if ($selectedStatus == 'pending') {
                        $message = "Total of %1 record(s) were not updated successfully as they were already Paid OR Canceled.";
                    } else if ($selectedStatus == 'cancel') {
                        $message = "Total of %1 record(s) were not updated successfully as they were already Paid.";
                    } else if ($selectedStatus == 'paid') {
                        $message = "Total of %1 record(s) were not updated successfully as they were already Canceled.";
                    }
                    $this->messageManager->addError(__($message, $notUpdateFlag));
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
