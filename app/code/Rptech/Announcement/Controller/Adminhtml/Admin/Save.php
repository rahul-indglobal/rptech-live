<?php

namespace Rptech\Announcement\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Rptech\Announcement\Model\Announcement;

/**
 * Class Save
 * @package Rptech\Announcement\Controller\Adminhtml\Admin
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Rptech\Announcement\Model\AnnouncementFactory
     */
    protected $announcementFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    public function __construct(
        Action\Context $context,
        \Rptech\Announcement\Model\AnnouncementFactory $announcementFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    )
    {
        $this->announcementFactory = $announcementFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }
            /** @var Announcement $model */
            $model = $this->announcementFactory->create();
            $entityId = $this->getRequest()->getParam('entity_id');
            if ($entityId) {
                $model->load($entityId);
            }
            $model->setData($data);
            if (isset($data['pdf'])) {
                $model->setPdf($data['pdf'][0]['name']);
            }
            try {
                $model->save();
                if($model->save()){
                    $this->messageManager->addSuccessMessage(__('You saved this Announcement.'));
                }

                $this->dataPersistor->clear('announcement');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e->getTraceAsString(), __('Something went wrong while saving the Announcement.'));
            }
            $this->dataPersistor->set('announcement', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
        }
        return $resultRedirect->setPath('*/*/edit');
    }

    /**
     * @param $model
     * @param $data
     * @param $resultRedirect
     * @return mixed
     */
    public function processReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';
        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getEntityId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect;
    }
}