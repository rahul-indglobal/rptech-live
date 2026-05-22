<?php

/**
 * Manageevents_Events Record Delete Controller.
 * @category  Manageevents_Events 
 * @package   Manageevents_Events
 * @author    Lalita Rajput
 */

namespace Manageevents\Events\Controller\Adminhtml\Grid;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem\Io\File;

class Save extends \Magento\Backend\App\Action {

    protected $_io;
    protected $_directoryList;
    protected $fileSystem;
    protected $uploaderFactory;
    protected $allowedExtensions = ['png', 'jpeg', 'jpg', 'gif'];
    protected $fileId1 = 'event_image_1';
    protected $fileId2 = 'event_image_2';
    protected $fileId3 = 'event_image_3';
    protected $fileId4 = 'event_image_4';
    protected $fileId5 = 'event_image_5';
    protected $fileId6 = 'event_image_6';
    protected $fileId7 = 'event_image_7';
    protected $fileId8 = 'event_image_8';
    protected $fileId9 = 'event_image_9';
    protected $fileId10 = 'event_image_10';

    /**
     * @var \Manageevents\Events\Model\GridFactory
     */
    var $gridFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Manageevents\Events\Model\GridFactory $gridFactory
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Manageevents\Events\Model\GridFactory $gridFactory, Filesystem $fileSystem, UploaderFactory $uploaderFactory, File $io, DirectoryList $directoryList
    ) {
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        parent::__construct($context);
        $this->gridFactory = $gridFactory;
        $this->_io = $io;
        $this->_directoryList = $directoryList;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute() {
////        echo count($_FILES);
//        echo "<pre>";
//        print_r($_POST);
//        echo "<pre>";
////        print_r(($_FILES));
//       die;



        $data = $this->getRequest()->getPostValue();
         //echo "<pre>";print_r($data);die;
        $delete = isset($data['slider_path']['delete']) ? $data['slider_path']['delete'] : "";

        //$destinationPath = $_SERVER['DOCUMENT_ROOT']. "html/Databank/home_sliders";
        $destinationPath = $this->getDestinationPath(); // . "home_sliders/";die;
        //for delete
        $delete1 = isset($data['event_image_1']['delete']) ? $data['event_image_1']['delete'] : "";
        $delete2 = isset($data['event_image_2']['delete']) ? $data['event_image_2']['delete'] : "";
        $delete3 = isset($data['event_image_3']['delete']) ? $data['event_image_3']['delete'] : "";
        $delete4 = isset($data['event_image_4']['delete']) ? $data['event_image_4']['delete'] : "";
        $delete5 = isset($data['event_image_5']['delete']) ? $data['event_image_5']['delete'] : "";
        $delete6 = isset($data['event_image_6']['delete']) ? $data['event_image_6']['delete'] : "";
        $delete7 = isset($data['event_image_7']['delete']) ? $data['event_image_7']['delete'] : "";
        $delete8 = isset($data['event_image_8']['delete']) ? $data['event_image_8']['delete'] : "";
        $delete9 = isset($data['event_image_9']['delete']) ? $data['event_image_9']['delete'] : "";
        $delete10 = isset($data['event_image_10']['delete']) ? $data['event_image_10']['delete'] : "";

        $data['event_image_1'] = isset($data['event_image_1']['value']) ? $data['event_image_1']['value'] : "";
        $data['event_image_2'] = isset($data['event_image_2']['value']) ? $data['event_image_2']['value'] : "";
        $data['event_image_3'] = isset($data['event_image_3']['value']) ? $data['event_image_3']['value'] : "";
        $data['event_image_4'] = isset($data['event_image_4']['value']) ? $data['event_image_4']['value'] : "";
        $data['event_image_5'] = isset($data['event_image_5']['value']) ? $data['event_image_5']['value'] : "";
        $data['event_image_6'] = isset($data['event_image_6']['value']) ? $data['event_image_6']['value'] : "";
        $data['event_image_7'] = isset($data['event_image_7']['value']) ? $data['event_image_7']['value'] : "";
        $data['event_image_8'] = isset($data['event_image_8']['value']) ? $data['event_image_8']['value'] : "";
        $data['event_image_9'] = isset($data['event_image_9']['value']) ? $data['event_image_9']['value'] : "";
        $data['event_image_10'] = isset($data['event_image_10']['value']) ? $data['event_image_10']['value'] : "";




        $currentFolder = getcwd(); 


            if($delete1==1){
                   if(!empty($data["event_image_1"])){
                                   $deleteFinal = explode('pub',  $data["event_image_1"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_1"] = '';
                               
                               }


            }
            if ($delete2==1) {

                   if(!empty($data["event_image_2"])){
                                   $deleteFinal = explode('pub',  $data["event_image_2"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_2"] = '';
                               
                               }
            }
            if ( $delete3==1) {
                   if(!empty($data["event_image_3"])){
                                   $deleteFinal = explode('pub',  $data["event_image_3"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_3"] = '';
                               
                               }
            }
            if ($delete4 ==1) {
                   if(!empty($data["event_image_4"])){
                                   $deleteFinal = explode('pub',  $data["event_image_4"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_4"] = '';
                               
                               }
            }
            if ($delete5 ==1) {
                   if(!empty($data["event_image_5"])){
                                   $deleteFinal = explode('pub',  $data["event_image_5"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_5"] = '';
                               
                               }
            }
            if ($delete6 ==1) {
                   if(!empty($data["event_image_6"])){
                                   $deleteFinal = explode('pub',  $data["event_image_6"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_6"] = '';
                               
                               }
            }
            if ($delete7 ==1) {
                   if(!empty($data["event_image_7"])){
                                   $deleteFinal = explode('pub',  $data["event_image_7"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_7"] = '';
                               
                               }
            }
            if ($delete8 ==1) {
                   if(!empty($data["event_image_8"])){
                                   $deleteFinal = explode('pub',  $data["event_image_8"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_8"] = '';
                               
                               }
            }
            if ($delete9 ==1) {
                   if(!empty($data["event_image_9"])){
                                   $deleteFinal = explode('pub',  $data["event_image_9"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_9"] = '';
                               
                               }
            }
            if ($delete10 ==1) {
                   if(!empty($data["event_image_10"])){
                                   $deleteFinal = explode('pub',  $data["event_image_10"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["event_image_10"] = '';
                               
                               }
            }





//        if ($delete1 == "1") {
//            str_replace("world","Peter","Hello world!");
//            unlink($data['event_image_1']);
//            $data['event_image_1'] = '';
//        }


        if (!$data) {
            $this->_redirect('events/grid/addrow');
            return;
        }
//        if ($delete == "1") {
//            unlink($destinationPath . $data['slider_path']);
//            $data['slider_path'] = '';
//        }
        try {
            $rowData = $this->gridFactory->create();
            //  echo "<pre>";print_r($rowData);die;
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();

            $filePath = "/rptech_events/" . $rowData->getId();
            $newfilepath = "rptech_events/" . $rowData->getId();
            $FolderPath = $this->_directoryList->getPath('media') . $filePath;
            if (!is_dir($FolderPath)) {
                $this->_io->mkdir($FolderPath, 0755);
            }


            if ($rowData->getId() && !empty($_FILES['event_image_1']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId1, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_2']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId2, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_3']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId3, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_4']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId4, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_5']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId5, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_6']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId6, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_7']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId7, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_8']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId8, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_9']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId9, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['event_image_10']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId10, $FolderPath, $newfilepath);
            }

            $this->messageManager->addSuccess(__('Event has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }

        $this->_redirect('events/grid/index');
    }

    public function deleteImage($filepath) {
        
    }

    public function SaveImages($eventID, $fileID, $FolderPath, $newfilepath) {
        //echo  "inside save img ";die;
        //upload code
        $uploader = $this->uploaderFactory->create(['fileId' => $fileID])
                ->setAllowCreateFolders(true)
                ->setAllowedExtensions($this->allowedExtensions)
                ->addValidateCallback('validate', $this, 'validateFile');
        $filename = $eventID . "_$fileID" . "." . $uploader->getFileExtension();

        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
        $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        $mediaUrlSave = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . "$newfilepath" . "/" . $filename;
        //$mediaUrlSave = "$FolderPath" . "/" . $filename;
        $rowData1 = $this->gridFactory->create();
        $newdata = array(
            $fileID => $mediaUrlSave
        );
        $rowData1->setData($newdata);

        $rowData1->setEntityId($eventID);

        $rowData1->save();

        if (!$uploader->save($FolderPath, $filename)) {
            throw new LocalizedException(
            __('File cannot be saved to path: $1', $destinationPath)
            );
        }
    }

    /**
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Manageevents_Events::save');
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
