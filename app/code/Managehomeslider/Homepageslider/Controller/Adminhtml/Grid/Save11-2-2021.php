<?php

/**
 * Managehomeslider_Homepageslider Record Delete Controller.
 * @category  Managehomeslider_Homepageslider 
 * @package   Managehomeslider_Homepageslider
 * @author    Lalita Rajput
 */

namespace Managehomeslider\Homepageslider\Controller\Adminhtml\Grid;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Save extends \Magento\Backend\App\Action {

	protected $fileSystem;
	protected $uploaderFactory;
	protected $allowedExtensions = ['png', 'jpeg', 'jpg', 'gif'];
	protected $fileId = 'slider_path';

	/**
	 * @var \Managehomeslider\Homepageslider\Model\GridFactory
	 */
	var $gridFactory;

	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Manageleaders\Leaders\Model\GridFactory $gridFactory
	 */
	public function __construct(
	\Magento\Backend\App\Action\Context $context, \Managehomeslider\Homepageslider\Model\GridFactory $gridFactory, Filesystem $fileSystem, UploaderFactory $uploaderFactory
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
		$delete = isset($data['slider_path']['delete']) ? $data['slider_path']['delete'] : "";
		//$destinationPath = $_SERVER['DOCUMENT_ROOT']. "html/Databank/home_sliders";
		$destinationPath = $this->getDestinationPath(); // . "home_sliders/";die;
		$data['slider_path'] = isset($data['slider_path']['value']) ? $data['slider_path']['value'] : "";


		if (!$data) {
			$this->_redirect('homepageslider/grid/addrow');
			return;
		}
		if ($delete == "1") {
			unlink($destinationPath . $data['slider_path']);
			$data['slider_path'] = '';
		}
		try {			
			if (!empty($data['entity_id'])) {
				$res = $this->getSliderSortOrder($data['sorting_order'], $data['entity_id']);
			} else {
				$res = $this->getSliderSortOrder($data['sorting_order'], '');
			}
			if ($res > 0) {
				throw new LocalizedException(
				__('Sort Order ' . $data['sorting_order'] . ' is already assigned.')
				);
			}

			$rowData = $this->gridFactory->create();
			//  echo "<pre>";print_r($rowData);die;
			$rowData->setData($data);
			if (isset($data['id'])) {
				$rowData->setEntityId($data['id']);
			}
			$rowData->save();
			if ($rowData->getId() && !empty($_FILES['slider_path']['name'])) {
				//upload code
				$uploader = $this->uploaderFactory->create(['fileId' => $this->fileId])
					->setAllowCreateFolders(true)
					->setAllowedExtensions($this->allowedExtensions)
					->addValidateCallback('validate', $this, 'validateFile');
				$filename = $rowData->getId() . "." . $uploader->getFileExtension();

				$_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
				$storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
				$currentStore = $storeManager->getStore();
				//$mediaUrlSave = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . "home_sliders" . "/" . $filename;
				$mediaUrlSave = "home_sliders" . "/" . $filename;
				$rowData1 = $this->gridFactory->create();
				$newdata = array(
					'slider_path' => $mediaUrlSave
				);
				$rowData1->setData($newdata);

				$rowData1->setEntityId($rowData->getId());

				$rowData1->save();

				if (!$uploader->save($destinationPath . "home_sliders/", $filename)) {
					throw new LocalizedException(
					__('File cannot be saved to path: $1', $destinationPath)
					);
				}
			}

			$this->messageManager->addSuccess(__('Slider has been successfully saved.'));
		} catch (\Exception $e) {
			$this->messageManager->addError(__($e->getMessage()));
		}
		$this->_redirect('homepageslider/grid/index');
	}

	/**
	 * @return bool
	 */
	protected function _isAllowed() {
		return $this->_authorization->isAllowed('Managehomeslider_Homepageslider::save');
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

	public function getSliderSortOrder($sortOrder, $entityID) {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$tableName = $resource->getTableName('rptech_homeSlider');
		$sql = "Select count(*) as cnt FROM " . $tableName . " where sorting_order = " . $sortOrder;
		if (!empty($entityID)) {
			if($entityID == 1 || $entityID == 2){
				$sql .= " and entity_id !=1 and entity_id !=2";
			}else{
				$sql .= " and entity_id !=" . $entityID;
			}
		}
		$result = $connection->fetchAll($sql);
//		print_r($result[0]['cnt']);die;
		return $result[0]['cnt'];
	}

}
