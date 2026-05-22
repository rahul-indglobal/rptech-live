<?php

namespace Rptech\Event\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Rptech\Event\Api\EventRepositoryInterface;
use Rptech\Event\Controller\Adminhtml\AbstractEvent;
use Rptech\Event\Model\EventFactory;

/**
 * Class Edit
 * @package Rptech\Event\Controller\Adminhtml\Admin
 */
class Edit extends AbstractEvent
{
    /**
     * @var EventFactory
     */
    protected $eventFactory;

    public function __construct(
        Registry $registry,
        EventRepositoryInterface $dataRepository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Context $context,
        EventFactory $eventFactory
    )
    {
        $this->eventFactory = $eventFactory;
        parent::__construct($registry, $dataRepository, $resultPageFactory, $resultForwardFactory, $context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        $model = $this->eventFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Event is no longer exists.'));
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
        $this->coreRegistry->register('event', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_Event::event');
        $resultPage->getConfig()->getTitle()->prepend(__('Event Form'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Event'));
        return $resultPage;
    }
}