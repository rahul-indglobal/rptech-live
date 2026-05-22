<?php

/**
 * Manageleaders_Leaders Record Delete Controller.
 * @category  Managefaq\Faq 
 * @package   Managefaq_Faq
 * @author    Lalita Rajput
 */

namespace Managefaq\Faq\Controller\Adminhtml\Grid;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Save extends \Magento\Backend\App\Action {

    protected $fileSystem;
    protected $uploaderFactory;
    protected $allowedExtensions = ['png', 'jpeg', 'jpg', 'gif'];
    protected $fileId = 'profile_photo';

    /**
     * @var \Manageleaders\Leaders\Model\GridFactory
     */
    var $gridFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Manageleaders\Leaders\Model\GridFactory $gridFactory
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Managefaq\Faq\Model\GridFactory $gridFactory, Filesystem $fileSystem, UploaderFactory $uploaderFactory
    ) {
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        parent::__construct($context);
        $this->gridFactory = $gridFactory;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute() {
        $data = $this->getRequest()->getPostValue();
       
       // echo "<pre>";print_r($_POST);die;
        //  echo $destinationPath = $this->getDestinationPath();die;
        if (!$data) {
            $this->_redirect('faq/grid/addrow');
            return;
        }
        try {
            $rowData = $this->gridFactory->create();
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();
            
            $this->messageManager->addSuccess(__('FAQ has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }

        $this->_redirect('faq/grid/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Managefaq_Faq::save');
    }

    public function validateFile($filePath) {
        // @todo
        // your custom validation code here
    }

    public function getDestinationPath() {
        return $this->fileSystem
                        ->getDirectoryWrite(DirectoryList::MEDIA)
                        ->getAbsolutePath('/');
    }

}
