<?php

/**
 * Manageleaders_Leaders Record Delete Controller.
 * @category  Leaders 
 * @package   Manageleaders_Leaders
 * @author    Lalita Rajput
 */

namespace Manageleaders\Leaders\Controller\Adminhtml\Grid;

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
    \Magento\Backend\App\Action\Context $context, \Manageleaders\Leaders\Model\GridFactory $gridFactory, Filesystem $fileSystem, UploaderFactory $uploaderFactory
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
       
        //  echo $destinationPath = $this->getDestinationPath();die;
        if (!$data) {
            $this->_redirect('leaders/grid/addrow');
            return;
        }
        try {
            
            $data['profile_photo'] = isset($data['profile_photo']['value']) ? $data['profile_photo']['value'] : "";
            $rowData = $this->gridFactory->create();
            $rowData->setData($data);
            
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();
            $destinationPath = $this->getDestinationPath() . "leaders_profile_photo";

            if ($rowData->getId() && !empty($_FILES['profile_photo']['name'])) {
                //upload code
                $uploader = $this->uploaderFactory->create(['fileId' => $this->fileId])
                        ->setAllowCreateFolders(true)
                        ->setAllowedExtensions($this->allowedExtensions)
                        ->addValidateCallback('validate', $this, 'validateFile');
                $filename = $rowData->getId() . "." . $uploader->getFileExtension();
                
                $_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
                $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
                $currentStore = $storeManager->getStore();
                $mediaUrlSave = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . "leaders_profile_photo" . "/" . $filename;
                $rowData1 = $this->gridFactory->create();
                $newdata = array(
                    'profile_photo' => $mediaUrlSave
                );
                $rowData1->setData($newdata);

                $rowData1->setEntityId($rowData->getId());

                $rowData1->save();

                if (!$uploader->save($destinationPath, $filename)) {
                    throw new LocalizedException(
                    __('File cannot be saved to path: $1', $destinationPath)
                    );
                }
            }

            $this->messageManager->addSuccess(__('Record has been added successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }

        $this->_redirect('leaders/grid/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Manageleaders_Leaders::save');
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
