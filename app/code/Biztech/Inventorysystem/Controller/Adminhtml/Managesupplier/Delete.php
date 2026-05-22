<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Managesupplier;

class Delete extends \Magento\Backend\App\Action
{
    protected $_supplierModel;

    /**
     * @param \Biztech\Inventorysystem\Model\Managesupplier $supplierModel
     */
    public function __construct(\Magento\Backend\App\Action\Context $context, \Biztech\Inventorysystem\Model\Managesupplier $supplierModel)
    {
        $this->_supplierModel = $supplierModel;
        parent::__construct($context);
    }

    /**
     * This function is used for remove the supplier
     * @return Void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id') ? $this->getRequest()->getParam('id') : $this->getRequest()->getParam('supplier_id');
        try {
            $supplier = $this->_supplierModel->load($id);
            $supplier->delete();
            $this->messageManager->addSuccess(
                __('Supplier was successfully deleted !')
            );
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'FOREIGN') !== false) {
                $this->messageManager->addError(__('You cannot delete this supplier as purchase orders are assigned to them.'));
            } else {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
