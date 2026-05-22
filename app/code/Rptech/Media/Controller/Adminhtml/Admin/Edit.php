<?php
/**
 * @author Rptech
 * @package Rptech_Media
 */
namespace Rptech\Media\Controller\Adminhtml\Admin;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Rptech\Media\Model\MediaFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Rptech\Media\Controller\Adminhtml\Admin
 */
class Edit extends Action
{
    protected $mediaFactory;
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
        mediaFactory $mediaFactory,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->mediaFactory = $mediaFactory;
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
        $model = $this->mediaFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Media is no longer exists.'));
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
        $this->coreRegistry->register('media',$model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_Media::Media')
            ->addBreadcrumb(__('Media'), __('Edit'))
            ->addBreadcrumb(__('Manage Media'), __('Manage Media'));
        $resultPage->getConfig()->getTitle()->prepend(__('Media'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add Media'));
        return $resultPage;
    }
}