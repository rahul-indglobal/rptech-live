<?php

/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\Grid\Controller\Adminhtml\Grid;

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
    protected $fileId1 = 'news_image_1';
    protected $fileId2 = 'news_image_2';
    protected $fileId3 = 'news_image_3';
    protected $fileId4 = 'news_image_4';
    protected $fileId5 = 'news_image_5';

    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    var $gridFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Webkul\Grid\Model\GridFactory $gridFactory
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Webkul\Grid\Model\GridFactory $gridFactory, Filesystem $fileSystem, UploaderFactory $uploaderFactory, File $io, DirectoryList $directoryList
    ) {
        parent::__construct($context);
        $this->gridFactory = $gridFactory;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->_io = $io;
        $this->_directoryList = $directoryList;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute() {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('grid/grid/addrow');
            return;
        }
        try {

            $delete1 = isset($data['news_image_1']['delete']) ? $data['news_image_1']['delete'] : "";
            $delete2 = isset($data['news_image_2']['delete']) ? $data['news_image_2']['delete'] : "";
            $delete3 = isset($data['news_image_3']['delete']) ? $data['news_image_3']['delete'] : "";
            $delete4 = isset($data['news_image_4']['delete']) ? $data['news_image_4']['delete'] : "";
            $delete5 = isset($data['news_image_5']['delete']) ? $data['news_image_5']['delete'] : "";
            $data['news_image_1'] = isset($data['news_image_1']['value']) ? $data['news_image_1']['value'] : "";
            $data['news_image_2'] = isset($data['news_image_2']['value']) ? $data['news_image_2']['value'] : "";
            $data['news_image_3'] = isset($data['news_image_3']['value']) ? $data['news_image_3']['value'] : "";
            $data['news_image_4'] = isset($data['news_image_4']['value']) ? $data['news_image_4']['value'] : "";
            $data['news_image_5'] = isset($data['news_image_5']['value']) ? $data['news_image_5']['value'] : "";

            $currentFolder = getcwd(); 


            if($delete1==1){
                   if(!empty($data["news_image_1"])){
                                   $deleteFinal = explode('pub',  $data["news_image_1"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["news_image_1"] = '';
                               
                               }


            }
            if ($delete2==1) {

                   if(!empty($data["news_image_2"])){
                                   $deleteFinal = explode('pub',  $data["news_image_2"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["news_image_2"] = '';
                               
                               }
            }
            if ( $delete3==1) {
                   if(!empty($data["news_image_3"])){
                                   $deleteFinal = explode('pub',  $data["news_image_3"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["news_image_3"] = '';
                               
                               }
            }
            if ($delete4 ==1) {
                   if(!empty($data["news_image_4"])){
                                   $deleteFinal = explode('pub',  $data["news_image_4"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["news_image_4"] = '';
                               
                               }
            }
            if ($delete5 ==1) {
                   if(!empty($data["news_image_5"])){
                                   $deleteFinal = explode('pub',  $data["news_image_5"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["news_image_5"] = '';
                               
                               }
            }

            $rowData = $this->gridFactory->create();
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();

            $filePath = "/rptech_news/" . $rowData->getId();
            $newfilepath = "rptech_news/" . $rowData->getId();
            $FolderPath = $this->_directoryList->getPath('media') . $filePath;
            if (!is_dir($FolderPath)) {
                $this->_io->mkdir($FolderPath, 0777);
            }


            if ($rowData->getId() && !empty($_FILES['news_image_1']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId1, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['news_image_2']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId2, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['news_image_3']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId3, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['news_image_4']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId4, $FolderPath, $newfilepath);
            }
            if ($rowData->getId() && !empty($_FILES['news_image_5']['name'])) {
                $this->SaveImages($rowData->getId(), $this->fileId5, $FolderPath, $newfilepath);
            }


            $this->messageManager->addSuccess(__('News has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('grid/grid/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Webkul_Grid::save');
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

}
