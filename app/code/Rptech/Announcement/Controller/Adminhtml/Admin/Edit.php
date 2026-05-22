<?php
/**
 * @author Rptech
 * @package Rptech_Announcement
 */
namespace Rptech\Announcement\Controller\Adminhtml\Admin;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Rptech\Announcement\Model\AnnouncementFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Rptech\Announcement\Controller\Adminhtml\Admin
 */
class Edit extends Action
{
    protected $announcementFactory;
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
        AnnouncementFactory $announcementFactory,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->announcementFactory = $announcementFactory;
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
        $model = $this->announcementFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Announcement is no longer exists.'));
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
        $this->coreRegistry->register('announcement',$model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_Announcement::Announcement')
            ->addBreadcrumb(__('Announcement'), __('Edit'))
            ->addBreadcrumb(__('Manage Announcement'), __('Manage Announcement'));
        $resultPage->getConfig()->getTitle()->prepend(__('Announcement'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add Announcement'));
        return $resultPage;
    }
}