<?php

/**
 * Managemedia_Rpmedia Record Delete Controller.
 * @category  Managemedia_Rpmedia
 * @package   Managemedia_Rpmedia
 * @author    Lalita Rajput
 */

namespace Managemedia\Rpmedia\Controller\Adminhtml\Grid;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem\Io\File;

class Save extends \Magento\Backend\App\Action {

	protected $fileSystem;
	protected $uploaderFactory;
	protected $allowedExtensions = ['png', 'jpeg', 'jpg', 'gif'];
	protected $_io;
	protected $_directoryList;
	protected $fileId1 = 'media_image_1';
	protected $fileId2 = 'media_image_2';
	protected $fileId3 = 'media_image_3';
	protected $fileId4 = 'media_image_4';
	protected $fileId5 = 'media_image_5';

	/**
	 * @var \Managemedia\Rpmedia\Model\GridFactory
	 */
	var $gridFactory;

	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Manageleaders\Leaders\Model\GridFactory $gridFactory
	 */
	public function __construct(
	\Magento\Backend\App\Action\Context $context, \Managemedia\Rpmedia\Model\GridFactory $gridFactory, Filesystem $fileSystem, UploaderFactory $uploaderFactory, File $io, DirectoryList $directoryList
	) {
		parent::__construct($context);
		$this->fileSystem = $fileSystem;
		$this->uploaderFactory = $uploaderFactory;
		$this->gridFactory = $gridFactory;
		$this->_io = $io;
		$this->_directoryList = $directoryList;
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
			$this->_redirect('rpmedia/grid/addrow');
			return;
		}
		try {

//echo "<pre>";print_r($data);die;


			$delete1 = isset($data['media_image_1']['delete']) ? $data['media_image_1']['delete'] : "";
			$delete2 = isset($data['media_image_2']['delete']) ? $data['media_image_2']['delete'] : "";
			$delete3 = isset($data['media_image_3']['delete']) ? $data['media_image_3']['delete'] : "";
			$delete4 = isset($data['media_image_4']['delete']) ? $data['media_image_4']['delete'] : "";
			$delete5 = isset($data['media_image_5']['delete']) ? $data['media_image_5']['delete'] : "";
			$data['media_image_1'] = isset($data['media_image_1']['value']) ? $data['media_image_1']['value'] : "";
			$data['media_image_2'] = isset($data['media_image_2']['value']) ? $data['media_image_2']['value'] : "";
			$data['media_image_3'] = isset($data['media_image_3']['value']) ? $data['media_image_3']['value'] : "";
			$data['media_image_4'] = isset($data['media_image_4']['value']) ? $data['media_image_4']['value'] : "";
			$data['media_image_5'] = isset($data['media_image_5']['value']) ? $data['media_image_5']['value'] : "";

           $currentFolder = getcwd(); 


            if($delete1==1){
				   if(!empty($data["media_image_1"])){
                                   $deleteFinal = explode('pub',  $data["media_image_1"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["media_image_1"] = '';
                               
                               }


            }
            if ($delete2==1) {

            	   if(!empty($data["media_image_2"])){
                                   $deleteFinal = explode('pub',  $data["media_image_2"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["media_image_2"] = '';
                               
                               }
            }
            if ( $delete3==1) {
            	   if(!empty($data["media_image_3"])){
                                   $deleteFinal = explode('pub',  $data["media_image_3"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["media_image_3"] = '';
                               
                               }
            }
            if ($delete4 ==1) {
            	   if(!empty($data["media_image_4"])){
                                   $deleteFinal = explode('pub',  $data["media_image_4"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["media_image_4"] = '';
                               
                               }
            }
            if ($delete5 ==1) {
            	   if(!empty($data["media_image_5"])){
                                   $deleteFinal = explode('pub',  $data["media_image_5"]);
                                   $deletedLink =  $currentFolder."/pub".$deleteFinal[1];
                                   @unlink( $deletedLink );
                                   $data["media_image_5"] = '';
                               
                               }
            }


                


                     
           


			// echo "<pre>";print_r($data);die;

			$rowData = $this->gridFactory->create();
			$rowData->setData($data);
			if (isset($data['id'])) {
				$rowData->setEntityId($data['id']);
			}
			$rowData->save();

			$filePath = "/rptech_media/" . $rowData->getId();
			$newfilepath = "rptech_media/" . $rowData->getId();
			$FolderPath = $this->_directoryList->getPath('media') . $filePath;
			if (!is_dir($FolderPath)) {
				$this->_io->mkdir($FolderPath, 0777);
			}


			if ($rowData->getId() && !empty($_FILES['media_image_1']['name'])) {
				$this->SaveImages($rowData->getId(), $this->fileId1, $FolderPath, $newfilepath);
			}
			if ($rowData->getId() && !empty($_FILES['media_image_2']['name'])) {
				$this->SaveImages($rowData->getId(), $this->fileId2, $FolderPath, $newfilepath);
			}
			if ($rowData->getId() && !empty($_FILES['media_image_3']['name'])) {
				$this->SaveImages($rowData->getId(), $this->fileId3, $FolderPath, $newfilepath);
			}
			if ($rowData->getId() && !empty($_FILES['media_image_4']['name'])) {
				$this->SaveImages($rowData->getId(), $this->fileId4, $FolderPath, $newfilepath);
			}
			if ($rowData->getId() && !empty($_FILES['media_image_5']['name'])) {
				$this->SaveImages($rowData->getId(), $this->fileId5, $FolderPath, $newfilepath);
			}

//            $rowData = $this->gridFactory->create();
//            $rowData->setData($data);
//            if (isset($data['id'])) {
//                $rowData->setEntityId($data['id']);
//            }
//            $rowData->save();

			$this->messageManager->addSuccess(__('Media has been successfully saved.'));
		} catch (\Exception $e) {
			$this->messageManager->addError(__($e->getMessage()));
		}

		$this->_redirect('rpmedia/grid/index');
	}

	/**
	 * @return bool
	 */
	protected function _isAllowed() {
		return $this->_authorization->isAllowed('Managemedia_Rpmedia::save');
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
