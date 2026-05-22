<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{

    protected $_coreRegistry = null;
    protected $resultPageFactory;
    protected $_warehouseModel;
    protected $_backendSession;

    /**
     * @param Action\Context                                  $context
     * @param \Magento\Framework\View\Result\PageFactory      $resultPageFactory
     * @param \Magento\Framework\Registry                     $registry
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_warehouseModel = $warehouseModel;
        $this->_backendSession = $context->getSession();
        parent::__construct($context);
    }

    /**
     * This function is used for the edit the warehouse
     * @return object
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');

        $model = $this->_warehouseModel;

        $registryObject = $this->_coreRegistry;

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
        $registryObject->register('warehouse_data', $model);
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? __('Edit Warehouse "') . $model->getWarehouseName() . '"' : __('Create Warehouse'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Biztech_Inventorysystemadvance::save');
    }

    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_Inventorysystemadvance::grid')
                ->addBreadcrumb(__('Warehouse'), __('Warehouse'))
                ->addBreadcrumb(__('Warehouse Infomation'), __('Warehouse Infomation'));
        return $resultPage;
    }
}
