<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\Inventorysystem;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action
{

    public $resultPageFactory;
    public $resultPage;
    protected $_registry;
    protected $_inventorysystemModel;
    
    /**
     * @param Context                                        $context
     * @param PageFactory                                    $resultPageFactory
     * @param \Magento\Framework\Registry                    $registry
     * @param \Biztech\Inventorysystem\Model\Inventorysystem $inventorysystemModel
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Biztech\Inventorysystem\Model\Inventorysystem $inventorysystemModel
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_registry = $registry;
        $this->_inventorysystemModel = $inventorysystemModel;
        $this->_backendSession = $context->getSession();
    }

    /**
     * This function is used for edit the stock
     * @return void
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');

        $model = $this->_inventorysystemModel;
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
        $registryObject->register('inventorysystem_data', $model);

        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->getConfig()->getTitle()->set((__('CSV file upload')));
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        return $this->resultPage;
        $this->_view->renderLayout();
    }
}
