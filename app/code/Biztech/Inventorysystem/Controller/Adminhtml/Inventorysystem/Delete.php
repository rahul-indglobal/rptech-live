<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\Inventorysystem;

class Delete extends \Magento\Backend\App\Action
{

    protected $_inventorysystemModel;

    /**
     * @param \Biztech\Inventorysystem\Model\Inventorysystem $inventorysystemModel
     */
    public function __construct(\Biztech\Inventorysystem\Model\Inventorysystem $inventorysystemModel)
    {
        
        $this->_inventorysystemModel = $inventorysystemModel;
    }

    /**
     * This function is used for remove the stock
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        try {
                $banner = $this->_inventorysystemModel->load($id);
                $banner->delete();
                $this->messageManager->addSuccess(
                    __('Delete successfully !')
                );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
