<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Managesupplier;

use Magento\Backend\App\Action\Context;

class Edit extends \Magento\Backend\App\Action
{

    protected $_resultPageFactory;
    protected $_supplierModel;
    protected $_supplierAddressModel;
    protected $_registry;

    /**
     * @param Context                                           $context
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Biztech\Inventorysystem\Model\Managesupplier     $supplierModel
     * @param \Biztech\Inventorysystem\Model\Managesupplieraddr $supplierAddressModel
     * @param \Magento\Framework\Registry                       $registry
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel,
        \Biztech\Inventorysystem\Model\Managesupplieraddr $supplierAddressModel,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_supplierModel = $supplierModel;
        $this->_supplierAddressModel = $supplierAddressModel;
        $this->_registry = $registry;
        $this->_backendSession = $context->getSession();
        parent::__construct($context);
    }

    /**
     * This function is used for edit the supplier
     * @return Object
     */
    public function execute()
    {
    
        $id = $this->getRequest()->getParam('id') ? $this->getRequest()->getParam('id') : $this->getRequest()->getParam('supplier_id');

        $model = $this->_supplierModel;
        $modelAddr = $this->_supplierAddressModel;

        $registryObject = $this->_registry;

        if ($id) {
            $model->load($id);
            $modelAddr->load($model->getSupplierAddress($model));
            if (!$model->getId()) {
                $this->messageManager->addError(__('Supplier does not exist'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->_backendSession->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
            $modelAddr->setData($data)->setSupplierId($model->getId())->setSupplierAddressId($model->getSupplierAddress($model));
        }
        $registryObject->register('inventorysystem_supplier_data', $model);
        $registryObject->register('inventorysystem_supplier_addr_data', $modelAddr);
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? __('Edit Supplier "') . $model->getFirstName() . ' ' . $model->getLastName() . '"' : __('Create Supplier'));
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
    /**
     * Authorization
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Biztech_Inventorysystem::save');
    }

    /**
     * Initialization
     * @return Object
     */
    protected function _initAction()
    {
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_Inventorysystem::grid')
                ->addBreadcrumb(__('Supplier'), __('Supplier'))
                ->addBreadcrumb(__('Supplier Infomation'), __('Supplier Infomation'));
        return $resultPage;
    }
}
