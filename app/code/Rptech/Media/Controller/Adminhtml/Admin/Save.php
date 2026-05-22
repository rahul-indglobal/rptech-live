<?php

namespace Rptech\Media\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Rptech\Media\Model\Media;

/**
 * Class Save
 * @package Rptech\Media\Controller\Adminhtml\Admin
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Rptech\Media\Model\MediaFactory
     */
    protected $mediaFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    public function __construct(
        Action\Context $context,
        \Rptech\Media\Model\MediaFactory $mediaFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    )
    {
        $this->mediaFactory = $mediaFactory;
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
            /** @var Media $model */
            $model = $this->mediaFactory->create();
            $entityId = $this->getRequest()->getParam('entity_id');
            if ($entityId) {
                $model->load($entityId);
            }
            $model->setData($data);
            try {
                $model->save();
                if($model->save()){
                    $this->messageManager->addSuccessMessage(__('You saved this Media.'));
                }

                $this->dataPersistor->clear('media');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e->getTraceAsString(), __('Something went wrong while saving the Media.'));
            }
            $this->dataPersistor->set('media', $data);
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