<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

use Magento\Backend\App\Action\Context;

class Delete extends \Magento\Backend\App\Action
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
     * This function is used for remove the warehouse
     * @return bool
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        try {
                $warehouse = $this->_warehouseModel->load($id);
            if ($warehouse->getPrimaryWarehouse() == 1) {
                $this->messageManager->addError(
                    __('Primary warehouse cannot be deleted!')
                );
            } else {
                $warehouse->delete();
                $this->messageManager->addSuccess(
                    __('Delete successfully !')
                );
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
