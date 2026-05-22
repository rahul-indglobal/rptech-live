<?php

namespace Rptech\HomepageSlider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Rptech\HomepageSlider\Controller\Adminhtml\Slider
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Rptech\HomepageSlider\Model\HomepageSliderFactory
     */
    protected $homepageSliderFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    public function __construct(
        Action\Context $context,
        \Rptech\HomepageSlider\Model\HomepageSliderFactory $homepageSliderFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    )
    {
        $this->homepageSliderFactory = $homepageSliderFactory;
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
            /** @var HomepageSliderFactory $model */
            $model = $this->homepageSliderFactory->create();
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
                    $this->messageManager->addSuccessMessage(__('You saved this Homepage Slider.'));
                }
                $this->dataPersistor->clear('homepageslider');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e->getTraceAsString(), __('Something went wrong while saving the Slider.'));
            }
            $this->dataPersistor->set('homepageslider', $data);
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