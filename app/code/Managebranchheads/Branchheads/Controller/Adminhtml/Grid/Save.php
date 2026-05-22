<?php

/**
 * Managebranchheads\Branchheads Record Delete Controller.
 * @category  Managebranchheads\Branchheads 
 * @package   Managebranchheads_Branchheads
 * @author    Lalita Rajput
 */

namespace Managebranchheads\Branchheads\Controller\Adminhtml\Grid;

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
     * @var \Managebranchheads\Branchheads\Model\GridFactory
     */
    var $gridFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Managebranchheads\Branchheads\Model\GridFactory $gridFactory
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Managebranchheads\Branchheads\Model\GridFactory $gridFactory, Filesystem $fileSystem, UploaderFactory $uploaderFactory
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
       // echo "<pre>";print_r($_POST);die;
        $data = $this->getRequest()->getPostValue();
        $delete = isset($data['profile_photo']['delete']) ? $data['profile_photo']['delete'] : "";
        //$destinationPath = $_SERVER['DOCUMENT_ROOT']. "html/Databank/rptech_awards";
        $destinationPath = $this->getDestinationPath() . "branchhead_profile_photo/";
        $data['profile_photo'] = isset($data['profile_photo']['value']) ? $data['profile_photo']['value'] : "";
        if (!$data) {
            $this->_redirect('branchheads/grid/addrow');
            return;
        }
        try {
            if ($delete == "1") {

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();
                $tableName = $resource->getTableName('rptech_manageBranchHead'); //gives table name with prefix
//Select Data from table
                $sql = "Select * FROM " . $tableName." where entity_id=".$data['entity_id'];
                $result = $connection->fetchRow($sql); 
                
               // echo "<pre>";print_r($result);die;

                unlink($destinationPath . $result['file_name']);
                $data['profile_photo'] = '';
            }
            $rowData = $this->gridFactory->create();
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }

            $rowData->save();


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
                $mediaUrlSave = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . "branchhead_profile_photo" . "/" . $filename;
                $rowData1 = $this->gridFactory->create();
                $newdata = array(
                    'profile_photo' => $mediaUrlSave,
                    'file_name' => $filename
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

            $this->messageManager->addSuccess(__('Branch head has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }

        $this->_redirect('branchheads/grid/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Managebranchheads_Branchheads::save');
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
