<?php
/**
 * @author Rptech
 * @package Rptech_FileUpload
 */
namespace Rptech\FileUpload\Controller\Adminhtml\Admin;

use Rptech\FileUpload\Model\FileUploadFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\FileUpload\Controller\Adminhtml\Admin
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_FileUpload::delete';

    /**
     * @var FileUploadFactory
     */
    private $fileUploadFactory;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;
    
    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param FileUploadFactory $fileUploadFactory
     */
    public function __construct(
        Action\Context $context,
        FileUploadFactory $fileUploadFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->fileUploadFactory = $fileUploadFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->fileUploadFactory->create();
                $model->load($entityId);
                $filename = $model->getFileName();
                $result = $model->delete();
                if ($result) {
                    
                    echo $filePath = $this->mediaDirectory->getAbsolutePath('fileupload/') . $filename;
                   
                    if ($this->mediaDirectory->isFile($filePath)) {
                        
                        $this->mediaDirectory->delete($filePath);
                    }
                }
                $this->messageManager->addSuccessMessage(__('The File has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a File to delete.'));
        return $resultRedirect->setPath('*/*');
    }
}
