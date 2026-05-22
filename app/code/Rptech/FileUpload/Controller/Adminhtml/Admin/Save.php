<?php

namespace Rptech\FileUpload\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Rptech\FileUpload\Model\FileUpload;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Save
 * @package Rptech\FileUpload\Controller\Adminhtml\Admin
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Rptech\FileUpload\Model\FileUploadFactory
     */
    protected $fileuploadFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var File
     */
    protected $file;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    
    public function __construct(
        Action\Context $context,
        \Rptech\FileUpload\Model\FileUploadFactory $fileuploadFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        File $file,
        \Magento\Framework\Filesystem $fileSystem,
        StoreManagerInterface $storeManager
    )
    {
        $this->fileuploadFactory = $fileuploadFactory;
        $this->dataPersistor = $dataPersistor;
        $this->file = $file;
        $this->fileSystem = $fileSystem;
        $this->storeManager = $storeManager;
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
            /** @var FileUpload $model */
            $model = $this->fileuploadFactory->create();
            $entityId = $this->getRequest()->getParam('entity_id');
            if ($entityId) {
                $model->load($entityId);
            }
            if (isset($data['file_upload'])) {
                $filename = $data['file_upload'][0]['name'];
            }
            $mediaBaseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $destinationPath = $mediaBaseUrl . 'fileupload/' . $filename;
            $model->setFileName($filename);
            $model->setLink($destinationPath);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the File.'));
                $this->dataPersistor->clear('fileupload');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e->getTraceAsString(), __('Something went wrong while saving the Financial Report.'));
            }
            $this->dataPersistor->set('fileupload', $data);
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
    
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Rptech_FileUpload::save');
    }
}