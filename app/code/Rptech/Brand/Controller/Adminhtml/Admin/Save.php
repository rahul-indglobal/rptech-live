<?php

namespace Rptech\Brand\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Rptech\Brand\Model\Brand;

/**
 * Class Save
 * @package Rptech\Brand\Controller\Adminhtml\Admin
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Rptech\Brand\Model\BrandFactory
     */
    protected $brandFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    public function __construct(
        Action\Context $context,
        \Rptech\Brand\Model\BrandFactory $brandFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    )
    {
        $this->brandFactory = $brandFactory;
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
            /** @var Brand $model */
            $model = $this->brandFactory->create();
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
//                    $images = $this->getRequest()->getParam('image');
//                    if (!empty($images)) {
//                        /** @var EventImage $modelImage */
//                        foreach ($images as $image) {
//                            $modelImage = $this->brandImageFactory->create();
//                            $modelImage->setEventEntityId($model->getEntityId());
//                            $modelImage->setImage($image['name']);
//                            $modelImage->save();
//                        }
//                    }
                    $this->messageManager->addSuccessMessage(__('You saved this Brand.'));
                }

                $this->dataPersistor->clear('brand');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e->getTraceAsString(), __('Something went wrong while saving the brand.'));
            }
            $this->dataPersistor->set('brand', $data);
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