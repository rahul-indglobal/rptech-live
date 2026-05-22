<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Managesupplier;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $_supplierModel;

    /**
     * @param \Magento\Backend\App\Action\Context           $context
     * @param \Biztech\Inventorysystem\Model\Managesupplier $supplierModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel
    ) {
        $this->_supplierModel = $supplierModel;
        parent::__construct($context);
    }

    /**
     * This function used for remove multiple supplier
     * @return Void
     */
    public function execute()
    {
        $ids = $this->getRequest()->getParam('id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select supplier(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $supplier = $this->_supplierModel->load($id);
                    $supplier->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of  %1 record(s) has been deleted', count($ids))
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
