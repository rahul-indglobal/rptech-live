<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

use Magento\Backend\App\Action\Context;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $_warehouseModel;

    /**
     * @param Context                                         $context
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     */
    public function __construct(
        Context $context,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
    ) {
        $this->_warehouseModel = $warehouseModel;
        parent::__construct($context);
    }
    
    /**
     * This function is used for remove multiple warehouses
     * @return void
     */
    public function execute()
    {
        $count = 0;
        $ids = $this->getRequest()->getParam('id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select warehouse(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_warehouseModel->load($id);
                    if ($row->getPrimaryWarehouse() == 1) {
                        $this->messageManager->addError(
                            __('Primary warehouse %1 cannot be deleted!', $row->getWarehouseName())
                        );
                    } else {
                        $row->delete();
                        $count++;
                    }
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($count))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
