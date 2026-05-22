<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

class MassDelete extends \Magento\Backend\App\Action
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
     * Remove multiple PO
     * @return Void
     */
    public function execute()
    {

        $ids = $this->getRequest()->getParam('purchaseorders');

        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select Purchase Order(s) to delete.'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_purchaseorderModel->load($id);
                    $row->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($ids))
                );
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'FOREIGN') !== false) {
                    $this->messageManager->addError(__('You cannot delete this supplier as purchase orders are assigned to them.'));
                } else {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/');
    }
}
