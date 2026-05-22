<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\Inventorysystem;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $inventorysystemModel;

    /**
     * @param \Biztech\Inventorysystem\Model\Inventorysystem $inventorysystemModel
     */
    public function __construct(\Biztech\Inventorysystem\Model\Inventorysystem $inventorysystemModel)
    {
        $this->_inventorysystemModel = $inventorysystemModel;
    }

    /**
     * This function is used for remove multiple stocks
     * @return Void
     */
    public function execute()
    {
        
         $ids = $this->getRequest()->getParam('id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select product(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_inventorysystemModel->load($id);
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
