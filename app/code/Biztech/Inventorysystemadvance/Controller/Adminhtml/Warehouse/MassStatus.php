<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

use Magento\Backend\App\Action\Context;

class MassStatus extends \Magento\Backend\App\Action
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
     * This function is used for change multiple products
     * @return void
     */
    public function execute()
    {
         $ids = $this->getRequest()->getParam('id');
         $status = $this->getRequest()->getParam('status');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select product(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_warehouseModel->load($id);
                    $row->setData('status', $status)
                            ->save();
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
