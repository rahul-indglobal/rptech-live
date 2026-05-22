<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Inventorysystem;

use Magento\Framework\Module\Manager;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Save extends \Magento\Backend\App\Action
{

    protected $moduleManager;
    protected $scopeConfig;
    protected $uploaderFactory;
    protected $_inventorysystemModel;
    protected $_fileSystem;
    protected $_productModel;
    protected $_stockitemRepository;
    protected $eventManager;
    protected $warehouseModel;
    protected $resourceConnection;
    protected $warehouseProductModel;

    /**
     * @param \Magento\Backend\App\Action\Context                       $context
     * @param Manager                                                   $moduleManager
     * @param ScopeConfigInterface                                      $scopeConfig
     * @param \Magento\Framework\File\UploaderFactory                   $uploaderFactory
     * @param \Biztech\Inventorysystem\Model\Inventorysystem            $inventorysystemModel
     * @param \Magento\Framework\Filesystem                             $fileSystem
     * @param \Magento\Catalog\Model\Product                            $productModel
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockitemRepository
     * @param \Magento\Framework\Event\Manager                          $eventManager
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse           $warehouseModel
     * @param \Magento\Framework\App\ResourceConnection                 $resourceConnection
     * @param \Biztech\Inventorysystemadvance\Model\Warehouseproduct    $warehouseProductModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Manager $moduleManager,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\File\UploaderFactory $uploaderFactory,
        \Biztech\Inventorysystem\Model\Inventorysystem $inventorysystemModel,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Catalog\Model\Product $productModel,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockitemRepository,
        \Magento\Framework\Event\Manager $eventManager,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Biztech\Inventorysystemadvance\Model\Warehouseproduct $warehouseProductModel
    ) {

        $this->moduleManager = $moduleManager;
        $this->scopeConfig = $scopeConfig;
        $this->uploaderFactory = $uploaderFactory;
        $this->_inventorysystemModel = $inventorysystemModel;
        $this->_fileSystem = $fileSystem;
        $this->_productModel = $productModel;
        $this->_stockitemRepository = $stockitemRepository;
        $this->eventManager = $eventManager;
        $this->warehouseModel = $warehouseModel;
        $this->resourceConnection = $resourceConnection;
        $this->warehouseProductModel = $warehouseProductModel;
        parent::__construct($context);
    }

    /**
     * This function is used for save the stock and statuses
     * @return Void
     */
    public function execute()
    {

        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->_inventorysystemModel;
                        
            $uploader = $this->uploaderFactory->create(['fileId' => 'csvfilename']);
            $files = $this->getRequest()->getFiles();
            $name = $files['csvfilename']['name'];
            $type = $files['csvfilename']['type'];

            if (isset($name) && $name != '') {
                try {
                    if ($type != 'text/csv') {
                        $this->messageManager->addError(__('Disallowed file type'));
                        if ($this->getRequest()->getParam('back')) {
                            $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                            return;
                        }
                        $this->_redirect('*/*/');
                        return;
                    }
                    // $uploader = $this->uploaderFactory->create(['fileId' => $name]);
                    $uploader->setAllowedExtensions(array('csv'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);

                    $mediaDirectory = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                    $path = $mediaDirectory->getAbsolutePath('bcinventorysystem/csv');

                    $result = $uploader->save($path, $name);

                    $file_path = $path . '/' . $result['file'];
                    chmod($file_path, 0777);

                    /* update inventory of products from csv */
                    $blankSKUArr = [];
                    $skuNotFoundArr = [];
                    if (($handle = fopen($path . "/" . $result['file'], "r")) !== false) {
                        $row = 0;
                        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                            if ($row != 0) {
                                $product = $this->_productModel;
                                $productID = $product->getIdBySku($data[0]);

                                if ($data[0] == "") {
                                    $blankSKUArr[] = $row + 1;
                                } else if ($data[0] != "" && $productID == false) {
                                    $skuNotFoundArr[] = $row + 1;
                                } else {
                                    if ($this->moduleManager->isEnabled('Biztech_Inventorysystemadvance') && isset($data[2])) {
                                        $this->eventManager = $this->eventManager;
                                        $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

                                        $this->warehouseFactory = $this->warehouseModel;

                                        $this->_resources = $this->resourceConnection;
                                        $connection = $this->_resources->getConnection();

                                        $tableName = $this->_resources->getTableName('bc_warehouse_product_is');
                                        $getWarehouseDetails = explode(";", $data[2]);
                                        $qty = [];
                                        $wareCode = [];
                                        if ($getWarehouseDetails[0] != '') {
                                            for ($i = 0; $i < count($getWarehouseDetails); $i++) {
                                                $warehouseParts = explode(":", $getWarehouseDetails[$i]);
                                                $qty[] = $warehouseParts[1];
                                                $wareCode[] = $warehouseParts[0];
                                            }
                                        }
                                        $oldRelIds = [];
                                        $getAllIdsBeforeSql = $connection->select()
                                                ->from($tableName, array('rel_id'))
                                                ->where('product_id = ' . $productID);
                                        $getIdsBefore = $connection->fetchAll($getAllIdsBeforeSql);
                                        if (is_array($getIdsBefore) && !empty($getIdsBefore[0])) {
                                            $oldRelIds = [];
                                            for ($j = 0; $j < count($getIdsBefore); $j++) {
                                                $oldRelIds[] = $getIdsBefore[$j]['rel_id'];
                                            }
                                        }
                                        $newRelIds = [];
                                        $assWareIdArr = [];
                                        for ($l = 0; $l < count($wareCode); $l++) {
                                            if ($wareCode[$l] != '') {
                                                $wareID = $this->warehouseFactory->load($wareCode[$l], 'warehouse_name')->getId();

                                                $assWareIdArr[] = $wareID;
                                                if (!$wareID) {
                                                    $msg = "Warehouse '" . $wareCode[$l] . "' assigned to product '" . $data[0] . "', does not exists.";
                                                    $this->messageManager->addError($msg);
                                                    if ($this->getRequest()->getParam('back')) {
                                                        $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                                                        return;
                                                    }
                                                    $this->_redirect('*/*/');
                                                }
                                                $getAsgnWareIdsSql = $connection->select()
                                                        ->from($tableName, array('rel_id'))
                                                        ->where('product_id = ' . $productID . ' AND warehouse_id=' . $wareID);
                                                $getAsgnWareIds = $connection->fetchAll($getAsgnWareIdsSql);
                                                if (count($getAsgnWareIds) > 0) {
                                                    $newRelIds[] = $getAsgnWareIds[0]['rel_id'];
                                                }
                                            }
                                        }
                                        if (!empty($oldRelIds) && empty($newRelIds)) {
                                            $msg = "Please assign warehouse to product '" . $data[0] . "'.";
                                            $this->messageManager->addError($msg);
                                            if ($this->getRequest()->getParam('back')) {
                                                $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                                                return;
                                            }
                                            $this->_redirect('*/*/');
                                        } else if (!empty($newRelIds)) {
                                            $totalWareQty = array_sum($qty);
                                            if ($totalWareQty != (int) $data[1]) {
                                                $msg = "Total Quantity assigned to warehouse is not same as quantity of product '" . $data[0] . "'. Please assign it proper!";
                                                $this->messageManager->addError($msg);
                                                if ($this->getRequest()->getParam('back')) {
                                                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                                                    return;
                                                }
                                                $this->_redirect('*/*/');
                                            }
                                        }
                                        $notAsgnWareIDs = array_diff($oldRelIds, $newRelIds);
                                        if (!in_array($defaultWarehouseID, $assWareIdArr)) {
                                            $msg = "Please Assign Default warehouse for product '" . $data[0] . "'.";
                                            $this->messageManager->addError($msg);
                                            if ($this->getRequest()->getParam('back')) {
                                                $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                                                return;
                                            }
                                            $this->_redirect('*/*/');
                                        }
                                        if (!empty($notAsgnWareIDs)) {
                                            foreach ($notAsgnWareIDs as $key => $relID) {
                                                $deleteWareProdRelModel = $this->warehouseModel;
                                                $deleteWareProdRelModel->setId($relID)->delete();
                                            }
                                        } else if ($getWarehouseDetails[0] != '') {
                                            for ($i = 0; $i < count($getWarehouseDetails); $i++) {
                                                $warehouseParts = explode(":", $getWarehouseDetails[$i]);
                                                $warehouseID = $this->warehouseFactory->load($warehouseParts[0], 'warehouse_name')->getId();
                                                $prepareSql = $connection->select()
                                                        ->from($tableName, array('rel_id', 'quantity'))
                                                        ->where('product_id = ' . $productID . ' AND warehouse_id = ' . $warehouseID);
                                                $getWarehouseData = $connection->fetchAll($prepareSql);
                                                
                                                $wareProdModel = $this->warehouseProductModel;
                                                
                                                if (!empty($getWarehouseData[0])) {
                                                    $wareProdModel->setId($getWarehouseData[0]['rel_id']);
                                                    $beforeQty = (int) $getWarehouseData[0]['quantity'];
                                                } else {
                                                    $beforeQty = null;
                                                }
                                                if ($beforeQty != $warehouseParts[1]) {
                                                    $wareProdModel->setWarehouseId($warehouseID);
                                                    $wareProdModel->setProductId($productID);
                                                    $wareProdModel->setPosition(0);
                                                    $wareProdModel->setQuantity($warehouseParts[1]);
                                                    $wareProdModel->save();

                                                    $warehouseLog = array('warehouse_name' => $warehouseParts[0],
                                                        'qty_before_upd' => $beforeQty,
                                                        'qty_after_upd' => $warehouseParts[1],
                                                        'product_id' => $productID);
                                                    //TODO
                                                    /* custom event fire */
                                                    $this->eventManager->dispatch('manage_stock_update_warehouse_log', array('warehouseLog' => $warehouseLog));
                                                }
                                            }
                                        }
                                    }
                                    $orgItm = $product->getOrigData('quantity_and_stock_status');
                                    $originalQty['original_quantity'] = $orgItm['qty'];
                                    $stockItem=$this->_stockitemRepository->getStockItem($productID);
                                    $stockItem->setData('is_in_stock', 1);
                                    $stockItem->setData('manage_stock', 1);
                                    $stockItem->setData('qty', $data[1]);

                                    $stockItem->save();

                                    /* custom event fire */
                                    // $this->eventManager->dispatch('is_manage_stock_grid_save', array('stockdata' => $stockItem->getData() + $originalQty));
                                }
                            }
                            $row++;
                        }
                        $notUpdRows = array_merge($blankSKUArr, $skuNotFoundArr);
                        sort($notUpdRows);
                        if (is_array($notUpdRows) && !empty($notUpdRows) && count($notUpdRows > 0)) {
                            $message = "Row(s) " . implode(",", $notUpdRows) . " were skipped as the SKU are either blank or not found in system";
                            $this->messageManager->addError(__($message));
                        }
                        fclose($handle);
                        $this->messageManager->addSuccess(__('CSV imported successfully'));
                    } else {
                        $this->messageManager->addError(__('CSV not found!'));
                    }
                } catch (\Magento\Framework\Model\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                } catch (\RuntimeException $e) {
                    $this->messageManager->addError($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addException($e, __('Something went wrong while importing csv'));
                }
            }

            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                return;
            }
            $this->_redirect('*/*/');
        }
    }
}
