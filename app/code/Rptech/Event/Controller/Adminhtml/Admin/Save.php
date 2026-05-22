<?php

namespace Rptech\Event\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Rptech\Event\Model\Event;
use Rptech\Event\Model\EventImage;

class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Rptech\Event\Model\EventFactory
     */
    protected $eventFactory;

    /**
     * @var \Rptech\Event\Model\EventImageFactory
     */
    protected $eventImageFactory;
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    public function __construct(
        Action\Context $context,
        \Rptech\Event\Model\EventFactory $eventFactory,
        \Rptech\Event\Model\EventImageFactory $eventImageFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    )
    {
        $this->eventFactory = $eventFactory;
        $this->eventImageFactory = $eventImageFactory;
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
            /** @var Event $model */
            $model = $this->eventFactory->create();
            $entityId = $this->getRequest()->getParam('entity_id');
            if ($entityId) {
                $model->load($entityId);
            }
            $model->setData($data);
            try {
                $model->save();
                if($model->save()){
                    $images = $this->getRequest()->getParam('image');
                    if (!empty($images)) {
                        /** @var EventImage $modelImage */
                        foreach ($images as $image) {
                            $modelImage = $this->eventImageFactory->create();
                            $modelImage->setEventEntityId($model->getEntityId());
                            $modelImage->setImage($image['name']);
                            $modelImage->save();
                        }
                        $this->messageManager->addSuccessMessage(__('You saved this Event.'));
                    }

                }

                $this->dataPersistor->clear('event');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the event.'));
            }
            $this->dataPersistor->set('event', $data);
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