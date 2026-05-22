<?php

/**
 * Manageawards_Awards Record Delete Controller.
 * @category  Manageawards_Awards 
 * @package   Manageawards_Awards
 * @author    Lalita Rajput
 */

namespace Manageawards\Awards\Controller\Adminhtml\Grid;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Save extends \Magento\Backend\App\Action {

    protected $fileSystem;
    protected $uploaderFactory;
    protected $allowedExtensions = ['png', 'jpeg', 'jpg', 'gif'];
    protected $fileId = 'award_image';

    /**
     * @var \Manageawards\Awards\Model\GridFactory
     */
    var $gridFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Manageawards\Awards\Model\GridFactory $gridFactory
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Manageawards\Awards\Model\GridFactory $gridFactory, Filesystem $fileSystem, UploaderFactory $uploaderFactory
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
        $delete = isset($data['award_image']['delete']) ? $data['award_image']['delete'] : "";
        //$destinationPath = $_SERVER['DOCUMENT_ROOT']. "html/Databank/rptech_awards";
        $destinationPath = $this->getDestinationPath(); // . "rptech_awards/";die;
        $data['award_image'] = isset($data['award_image']['value']) ? $data['award_image']['value'] : "";


        if (!$data) {
            $this->_redirect('awards/grid/addrow');
            return;
        }
        if ($delete == "1") {
            unlink($destinationPath . $data['award_image']);
            $data['award_image'] = '';
        }
        try {
            $rowData = $this->gridFactory->create();
            //  echo "<pre>";print_r($rowData);die;
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();




            if ($rowData->getId() && !empty($_FILES['award_image']['name'])) {
                //upload code
                $uploader = $this->uploaderFactory->create(['fileId' => $this->fileId])
                        ->setAllowCreateFolders(true)
                        ->setAllowedExtensions($this->allowedExtensions)
                        ->addValidateCallback('validate', $this, 'validateFile');
                $filename = $rowData->getId() . "." . $uploader->getFileExtension();

                $_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
                $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
                $currentStore = $storeManager->getStore();
                $mediaUrlSave = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . "rptech_awards" . "/" . $filename;
                //$mediaUrlSave = "rptech_awards" . "/" . $filename;
                $rowData1 = $this->gridFactory->create();
                $newdata = array(
                    'award_image' => $mediaUrlSave
                );
                $rowData1->setData($newdata);

                $rowData1->setEntityId($rowData->getId());

                $rowData1->save();

                if (!$uploader->save($destinationPath . "rptech_awards/", $filename)) {
                    throw new LocalizedException(
                    __('File cannot be saved to path: $1', $destinationPath)
                    );
                }
            }

            $this->messageManager->addSuccess(__('Record has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }

        $this->_redirect('awards/grid/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Manageawards_Awards::save');
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
