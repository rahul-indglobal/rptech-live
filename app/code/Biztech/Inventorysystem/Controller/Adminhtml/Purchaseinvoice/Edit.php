<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Purchaseinvoice;

class Edit extends \Magento\Backend\App\Action
{
    protected $_purchaseinvoiceModel;
    protected $_registry;
    protected $_backendSession;

    /**
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoice $purchaseinvoiceModel
     * @param \Magento\Framework\Registry                    $registry
     * @param \Magento\Backend\Model\Session                 $backendSession
     */
    public function __construct(
        \Biztech\Inventorysystem\Model\Purchaseinvoice $purchaseinvoiceModel,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\Session $backendSession
    ) {
        $this->_purchaseinvoiceModel = $purchaseinvoiceModel;
        $this->_registry = $registry;
        $this->_backendSession = $backendSession;
    }

    /**
     * Purchase invoice edit
     * @return Void
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        
        $model = $this->_purchaseinvoiceModel;
        
        $registryObject = $this->_registry;
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This row no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->_backendSession->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $registryObject->register('inventorysystem_purchaseinvoice', $model);
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
