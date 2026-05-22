<?php
/**
 * @author Rptech
 * @package Rptech_FileUpload
 */
namespace Rptech\FileUpload\Controller\Adminhtml\Admin;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Rptech\FileUpload\Model\FileUploadFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Rptech\FileUpload\Controller\Adminhtml\Admin
 */
class Edit extends Action
{
    /**
     * @var FileUploadFactory
     */
    protected $fileUploadFactory;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param FileUploadFactory $fileUploadFactory
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        FileUploadFactory $fileUploadFactory,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->fileUploadFactory = $fileUploadFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
//        die('i m printing');
        $entityId = $this->getRequest()->getParam('entity_id');
        $model = $this->fileUploadFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This File is no longer exists.'));
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
        $this->coreRegistry->register('fileupload',$model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_FileUpload::FileUpload')
            ->addBreadcrumb(__('FileUpload'), __('Edit'))
            ->addBreadcrumb(__('Manage FileUpload'), __('Manage FileUpload'));
        $resultPage->getConfig()->getTitle()->prepend(__('FileUpload'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add File'));
        return $resultPage;
    }
}