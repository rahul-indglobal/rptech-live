<?php
/**
 * @author Rptech
 * @package Rptech_Awards
 */
namespace Rptech\Awards\Controller\Adminhtml\Admin;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Rptech\Awards\Model\AwardsFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Rptech\Awards\Controller\Adminhtml\Admin
 */
class Edit extends Action
{
    protected $awardsFactory;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Registry
     */
    protected $coreRegistry;

    public function __construct(
        Context $context,
        AwardsFactory $awardsFactory,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->awardsFactory = $awardsFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        $model = $this->awardsFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Award is no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        //Set entered data if was error when do save
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->coreRegistry->register('award',$model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_Awards::Award')
            ->addBreadcrumb(__('Award'), __('Edit'))
            ->addBreadcrumb(__('Manage Award'), __('Manage Award'));
        $resultPage->getConfig()->getTitle()->prepend(__('Award'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add Award'));
        return $resultPage;
    }
}