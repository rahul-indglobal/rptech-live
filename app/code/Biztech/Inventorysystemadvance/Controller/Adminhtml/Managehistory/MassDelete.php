<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Managehistory;

use Magento\Backend\App\Action\Context;

class MassDelete extends \Magento\Backend\App\Action
{

    protected $_managehistoryModel;

    /**
     * @param Context                                             $context            [description]
     * @param \Biztech\Inventorysystemadvance\Model\Managehistory $managehistoryModel [description]
     */
    public function __construct(
        Context $context,
        \Biztech\Inventorysystemadvance\Model\Managehistory $managehistoryModel
    ) {
        $this->_managehistoryModel = $managehistoryModel;
        parent::__construct($context);
    }
    
    /**
     * This function is used for the remove multiple history row
     * @return void
     */
    public function execute()
    {
        
         $ids = $this->getRequest()->getParam('id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select product(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_managehistoryModel->load($id);
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
