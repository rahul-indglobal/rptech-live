<?php

namespace Rptech\Awards\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Rptech\Awards\Model\Awards;

/**
 * Class Save
 * @package Rptech\Awards\Controller\Adminhtml\Admin
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Rptech\Awards\Model\AwardsFactory
     */
    protected $awardsFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    public function __construct(
        Action\Context $context,
        \Rptech\Awards\Model\AwardsFactory $awardsFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    )
    {
        $this->awardsFactory = $awardsFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        //echo '<pre>'; print_r($data); echo '</pre>'; die('endhere');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }
            /** @var Awards $model */
            $model = $this->awardsFactory->create();
            $entityId = $this->getRequest()->getParam('entity_id');
            if ($entityId) {
                $model->load($entityId);
            }
            $model->setData($data);
            if (isset($data['image'])) {
                $model->setImage($data['image'][0]['name']);
            }
            try {
                $model->save();
                if($model->save()){
                    $this->messageManager->addSuccessMessage(__('You saved this Award.'));
                }
                $this->dataPersistor->clear('award');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e->getTraceAsString(), __('Something went wrong while saving the Award.'));
            }
            $this->dataPersistor->set('award', $data);
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