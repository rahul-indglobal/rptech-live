<?php

namespace Biztech\Inventorysystemadvance\Helper;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_countryFactory;
    protected $_backendUrl;
    protected $moduleManager;
    protected $scopeConfig;
    protected $_resourceConnection;
    protected $_warehouseProductModel;
    protected $_warehouseModel;
    protected $_stockRegistry;
    protected $_productModel;
    protected $_eventManager;
    protected $_objectManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Biztech\Inventorysystemadvance\Model\Warehouseproduct $warehouseProductModel,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Catalog\Model\Product $productModel,
        \Magento\Framework\Event\Manager $eventManager,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_countryFactory = $countryFactory;
        $this->_backendUrl = $backendUrl;
        $this->moduleManager = $context->getModuleManager();
        $this->scopeConfig = $context->getScopeConfig();
        $this->messageManager = $messageManager;
        $this->responseFactory = $responseFactory;
        $this->_resourceConnection = $resourceConnection;
        $this->_warehouseProductModel = $warehouseProductModel;
        $this->_warehouseModel = $warehouseModel;
        $this->_stockRegistry = $stockRegistry;
        $this->_productModel = $productModel;
        $this->_eventManager = $eventManager;
        $this->_objectManager = $objectManager;
        parent::__construct($context);
    }

    /**
     * get products tab Url in admin
     * @return string
     */
    public function getProductsGridUrl()
    {
        return $this->_backendUrl->getUrl('inventorysystemadvance/warehouse/products', ['_current' => true]);
    }

    public function updateInventory($getParams, $getCurWar = '', $removed_product = '', $module = '')
    {
        $this->_resources = $this->_resourceConnection;
        $connection = $this->_resources->getConnection();
        $tableName = $this->_resources->getTableName('bc_warehouse_product_is');

        if ($this->moduleManager->isEnabled('Biztech_Inventorysystemadvance')) {
            $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            if ($removed_product != '' && $module == 'warehouse' && is_array($removed_product)) {
                if (!empty($removed_product)) {
                    $this->warehouseProductsFactory = $this->_warehouseProductModel;
                    if ($getCurWar == $defaultWarehouseID) {
                        $prod_name_arr = array();
                        foreach ($removed_product as $prodId => $data) {
                            $get_remv_ids = $connection->select()
                                    ->from($tableName, array('rel_id', 'warehouse_id', 'quantity'))
                                    ->where('product_id = ' . $prodId);
                            $get_remv_warehouse = $connection->fetchAll($get_remv_ids);
                            if (count($get_remv_warehouse) > 1) {
                                $get_rel_id_sql = $connection->select()
                                        ->from($tableName, array('rel_id', 'quantity'))
                                        ->where('product_id = ' . $prodId . ' AND warehouse_id = ' . $defaultWarehouseID);
                                $get_rel_id = $connection->fetchAll($get_rel_id_sql);
                                $remove_ware_model = $this->warehouseProductsFactory;
                                $remove_ware_model->setId($get_rel_id[0]['rel_id']);
                                $remove_ware_model->setWarehouseId($defaultWarehouseID);
                                $remove_ware_model->setProductId($prodId);
                                $remove_ware_model->setPosition(0);
                                $remove_ware_model->setQuantity(0);
                                $remove_ware_model->save();

                                $dec_stock_model = $this->_stockRegistry->getStockItem($prodId);
                                
                                $get_qty = (int) $dec_stock_model->getQty();
                                $upd_qty = $get_qty - (int) $get_rel_id[0]['quantity'];

                                $dec_stock_model->setData('qty', $upd_qty);
                                $dec_stock_model->save();
                                $prodName = $this->_productModel->load($prodId)->getName();
                                $prod_name_arr[] = $prodName;
                            } else if (count($get_remv_warehouse) == 1) {
                                if ($get_remv_warehouse[0]['warehouse_id'] == $defaultWarehouseID) {
                                    $prodName = $this->_productModel->load($prodId)->getName();
                                    $prod_name_arr[] = $prodName;
                                } else {
                                    $get_rel_id_sql = $connection->select()
                                            ->from($tableName, array('rel_id', 'quantity'))
                                            ->where('product_id = ' . $prodId . ' AND warehouse_id = ' . $defaultWarehouseID);
                                    $get_rel_id = $connection->fetchAll($get_rel_id_sql);
                                    $remove_ware_model = $this->warehouseProductsFactory;
                                    $remove_ware_model->setId($get_rel_id[0]['rel_id']);
                                    $remove_ware_model->delete();
                                }
                            }
                        }
                        if (isset($prod_name_arr) && !empty($prod_name_arr)) {
                            $this->messageManager->addError("Product(s) '" . implode("','", $prod_name_arr) . "' cannot be removed from default warehouse, we have removed assign quantity from this warhouse & from product also.");
                        }
                    } else {
                        foreach ($removed_product as $prodId => $data) {
                            $get_remv_ids = $connection->select()
                                    ->from($tableName, array('rel_id', 'warehouse_id', 'quantity'))
                                    ->where('product_id = ' . $prodId . ' AND warehouse_id = ' . $getCurWar);
                            $get_remv_warehouse = $connection->fetchAll($get_remv_ids);
                            $get_def_ware_sql = $connection->select()
                                    ->from($tableName, array('rel_id', 'warehouse_id', 'quantity'))
                                    ->where('product_id = ' . $prodId . ' AND warehouse_id = ' . $defaultWarehouseID);
                            $get_def_ware = $connection->fetchAll($get_def_ware_sql);
                            $add_def_ware_model = $this->warehouseProductsFactory;
                            if (!empty($get_def_ware[0])) {
                                $upd_qty = (int) $get_def_ware[0]['quantity'] + (int) $get_remv_warehouse[0]['quantity'];
                                $add_def_ware_model->setId($get_def_ware[0]['rel_id']);
                            } else {
                                $upd_qty = (int) $get_remv_warehouse[0]['quantity'];
                                $add_def_ware_model->setWarehouseId($defaultWarehouseID);
                                $add_def_ware_model->setProductId($prodId);
                                $add_def_ware_model->setPosition(0);
                            }
                            $add_def_ware_model->setQuantity($upd_qty);
                            $add_def_ware_model->save();
                            $remv_prod_warehouse = $this->warehouseProductsFactory;
                            $remv_prod_warehouse->setId($get_remv_warehouse[0]['rel_id']);
                            $remv_prod_warehouse->delete();
                        }
                    }
                }
            }
        }

        if ($module != '') {
            $jdecParams = $getParams;
        } else {
            $getParams = str_replace(array('$#$', "'"), array(',', '"'), $getParams);
            $getParams = "[" . $getParams . "]";
            $jdecParams = json_decode($getParams, true);
        }
        for ($i = 0; $i < count($jdecParams); $i++) {
            if ($jdecParams[$i]['data']['total_qty'] != "") {
                $productID = $jdecParams[$i]['id'];
                $qtyLevel = $jdecParams[$i]['data']['qty_level'];
                $enteredQty = (int) $jdecParams[$i]['data']['total_qty'];

                $stockItem = $this->_stockRegistry->getStockItem($productID);
                $getQty = (int) $stockItem->getQty();

                if ($this->moduleManager->isEnabled('Biztech_Inventorysystemadvance')) {
                    if ($getCurWar) {
                        $getIncrIds = $connection->select()
                                ->from($tableName, array('rel_id', 'quantity'))
                                ->where('product_id = ' . $productID . ' AND warehouse_id = ' . $getCurWar);
                        $getWarehouseData = $connection->fetchAll($getIncrIds);

                        /* warehouse qty update calculation */
                        
                        $returnValue = $this->updateWarehouseStock($getCurWar, $getWarehouseData, $qtyLevel, $enteredQty, $productID, $getQty, 'Selected');
                        if ($returnValue === false) {
                            return false;
                        }
                    }
                }


                if ($getQty > $enteredQty && ($getQty - $enteredQty) > $this->scopeConfig->getValue('cataloginventory/item_options/min_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
                    $stockItem->setData('is_in_stock', 1);
                    $stockItem->setData('manage_stock', 1);
                }
                /* check qty increase or decrease */
                if ($qtyLevel == 1) {
                    $qty = $getQty + $enteredQty;
                } else if ($qtyLevel == 2) {
                    $qty = $getQty - $enteredQty;
                }
                $product = $this->_productModel->load($productID);
                $orgItm = $product->getOrigData('quantity_and_stock_status');
                $originalQty['original_quantity'] = $orgItm['qty'];
                $stockItem->setData('qty', $qty);
                $stockItem->save();

                $this->eventManager = $this->_eventManager;
                $this->eventManager->dispatch('is_manage_stock_grid_save', array('stockdata' => $stockItem->getData() + $originalQty, 'comment' => $jdecParams[$i]['data']['comment']));
            }
        }

        if ($module == '') {
            $this->messageManager->addSuccess(__('Inventory of total %1 product(s) were successfully updated', count($jdecParams)));
        }
    }

    protected function updateWarehouseStock($warehouseID, $getWarehouseData, $qtyLevel, $enteredQty, $productID, $curProdQty, $warehouse)
    {
        $wareProdModel = $this->_objectManager->create('Biztech\Inventorysystemadvance\Model\Warehouseproduct');
        $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $relID = null;
        if ($qtyLevel == 1) {
            if (!empty($getWarehouseData[0])) {
                $relID = $getWarehouseData[0]['rel_id'];
                $wareQty = (int) $getWarehouseData[0]['quantity'] + $enteredQty;
            } else if (empty($getWarehouseData[0])) {
                $this->_resources = $this->_resourceConnection;
                $connection = $this->_resources->getConnection();
                $tableName = $this->_resources->getTableName('bc_warehouse_product_is');
                $getWarehousesSql = $connection->select()
                        ->from($tableName, array('rel_id', 'quantity'))
                        ->where('product_id = ' . $productID);
                $getWarehouse = $connection->fetchAll($getWarehousesSql);
                if (empty($getWarehouse[0])) {
                    if ($warehouseID == $defaultWarehouseID) {
                        $wareQty = $curProdQty + $enteredQty;
                    } else {
                        $wareQty = $enteredQty;
                    }
                    $defWareQty = $curProdQty;
                } else {
                    $wareQty = $enteredQty;
                }
            }
        } else if ($qtyLevel == 2) {
            if (!empty($getWarehouseData[0])) {
                $relID = $getWarehouseData[0]['rel_id'];
                if ((int) $getWarehouseData[0]['quantity'] >= $enteredQty) {
                    $wareQty = (int) $getWarehouseData[0]['quantity'] - $enteredQty;
                } else {
                    $i = $warehouse . " warehouse quantity is less than the Quantity you entered for product SKU: '" . $this->_productModel->load($productID)->getSku() . "'. Please enter proper quantity!";
                    
                    $this->messageManager->addError($i);

                    $backendUrl = $this->_backendUrl;
                    $redirect = $backendUrl->getUrl('*/*/*');
                    $this->responseFactory->create()->setRedirect($redirect)->sendResponse();
                    return false;
                }
            } else if (empty($getWarehouseData[0])) {
                $i = "Product '" . $this->_productModel->load($productID)->getSku() . "' is not assigned in " . $warehouse . " warehouse.";
                $this->messageManager->addError($i);
                $backendUrl = $this->_backendUrl;
                $redirect = $backendUrl->getUrl('*/*/*');
                $this->responseFactory->create()->setRedirect($redirect)->sendResponse();
                return false;
            }
        }

        if (isset($relID) && $relID != null) {
            $wareProdModel->setId($relID);
        }

        $wareProdModel->setWarehouseId($warehouseID);
        $wareProdModel->setProductId($productID);
        $wareProdModel->setPosition(0);
        $wareProdModel->setQuantity($wareQty);
        $wareProdModel->save();

        if (isset($defWareQty) && $warehouseID != $defaultWarehouseID) {
            $defWareProdModel = $this->_warehouseProductModel;
            $defWareProdModel->setWarehouseId($defaultWarehouseID);
            $defWareProdModel->setProductId($productID);
            $defWareProdModel->setPosition(0);
            $defWareProdModel->setQuantity($defWareQty);
            $defWareProdModel->save();
        }

        $warehouseName = $this->_warehouseModel->load($warehouseID)->getWarehouseName();

        if (!empty($getWarehouseData[0])) {
            $qtyBeforeUpd = (int) $getWarehouseData[0]['quantity'];
        } else {
            $qtyBeforeUpd = 0;
        }

        if ($qtyBeforeUpd != $wareQty) {
            $warehouseLog = array('warehouse_name' => $warehouseName,
                'qty_before_upd' => $qtyBeforeUpd,
                'qty_after_upd' => $wareQty,
                'product_id' => $productID);
            
            $this->eventManager = $this->_eventManager;
            $this->eventManager->dispatch('manage_stock_update_warehouse_log', $warehouseLog);
        }
    }

    public function getWarehouseDetails($productID)
    {
        $this->_resources = $this->_resourceConnection;
        $connection = $this->_resources->getConnection();
        $tableName = $this->_resources->getTableName('bc_warehouse_product_is');

        $getIncrIds = $connection->select()
                ->from($tableName, array('warehouse_id', 'quantity'))
                ->where('product_id = ' . $productID);
        $getData = $connection->fetchAll($getIncrIds);

        if (!empty($getData[0])) {
            for ($i = 0; $i < count($getData); $i++) {
                $warehouseArr[] = array('warehouse_id' => $getData[$i]['warehouse_id'], 'warehouse_code' => $this->_warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName(), 'quantity' => $getData[$i]['quantity']);
            }
            return $warehouseArr;
        } else {
            return false;
        }
    }
  
    public function deleteWarehouse($id)
    {

        $currentWarehouseID = $id;
        $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select');

        if ($defaultWarehouseID && $currentWarehouseID == $defaultWarehouseID) {
        } else if ($defaultWarehouseID && $currentWarehouseID != $defaultWarehouseID) {
            $connection = $this->_resourceConnection->getConnection();
            $tableName = $this->_resourceConnection->getTableName('bc_warehouse_product_is');

            $selectData = $connection->select()
                    ->from($tableName, array('rel_id', 'product_id', 'quantity'))
                    ->where('warehouse_id = ' . $currentWarehouseID);
            $getCurrentWarehouseData = $connection->fetchAll($selectData);
            
            if (!empty($getCurrentWarehouseData[0])) {
                for ($i = 0; $i < count($getCurrentWarehouseData); $i++) {
                    echo "Id :- ".$getCurrentWarehouseData[$i]['rel_id'];
                    echo "<br/>";
                    $selectDefWareData = $connection->select()
                            ->from($tableName, array('rel_id', 'quantity'))
                            ->where('warehouse_id=' . $defaultWarehouseID . ' AND product_id=' . $getCurrentWarehouseData[$i]['product_id']);
                    $getDefaultWarehouseData = $connection->fetchAll($selectDefWareData);

                    if (!empty($getDefaultWarehouseData[0])) {
                        $defaultWarehouseQty = (int) $getDefaultWarehouseData[0]['quantity'] + (int) $getCurrentWarehouseData[$i]['quantity'];
                        $relID = $getDefaultWarehouseData[0]['rel_id'];
                    } else {
                        $defaultWarehouseQty = (int) $getCurrentWarehouseData[$i]['quantity'];
                    }

                    $curWareProdModel = $this->_warehouseProductModel;
                    $curWareProdModel->setId($getCurrentWarehouseData[$i]['rel_id'])->delete();
                }
            }
            $model = $this->_warehouseModel;
            $model->setId($currentWarehouseID)
                    ->delete();
        }
    }

    public function barocdeImage($barcode)
    {
        $text = (isset($_REQUEST["text"]) ? $_REQUEST["text"] : "0");
        $size = (isset($_REQUEST["size"]) ? $_REQUEST["size"] : "20");
        $orientation = (isset($_REQUEST["orientation"]) ? $_REQUEST["orientation"] : "horizontal");
        $code_type = (isset($_REQUEST["codetype"]) ? $_REQUEST["codetype"] : "code128");
        $code_string = "";

// Translate the $text into barcode the correct $code_type
        if (in_array(strtolower($code_type), array("code128", "code128b"))) {
            $chksum = 104;
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "\`" => "111422", "a" => "121124", "b" => "121421", "c" => "141122", "d" => "141221", "e" => "112214", "f" => "112412", "g" => "122114", "h" => "122411", "i" => "142112", "j" => "142211", "k" => "241211", "l" => "221114", "m" => "413111", "n" => "241112", "o" => "134111", "p" => "111242", "q" => "121142", "r" => "121241", "s" => "114212", "t" => "124112", "u" => "124211", "v" => "411212", "w" => "421112", "x" => "421211", "y" => "212141", "z" => "214121", "{" => "412121", "|" => "111143", "}" => "111341", "~" => "131141", "DEL" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "FNC 4" => "114131", "CODE A" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ($X = 1; $X <= strlen($text); $X++) {
                $activeKey = substr($text, ($X - 1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum = ($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211214" . $code_string . "2331112";
        } elseif (strtolower($code_type) == "code128a") {
            $chksum = 103;
            $text = strtoupper($text); // Code 128A doesn't support lower case
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "NUL" => "111422", "SOH" => "121124", "STX" => "121421", "ETX" => "141122", "EOT" => "141221", "ENQ" => "112214", "ACK" => "112412", "BEL" => "122114", "BS" => "122411", "HT" => "142112", "LF" => "142211", "VT" => "241211", "FF" => "221114", "CR" => "413111", "SO" => "241112", "SI" => "134111", "DLE" => "111242", "DC1" => "121142", "DC2" => "121241", "DC3" => "114212", "DC4" => "124112", "NAK" => "124211", "SYN" => "411212", "ETB" => "421112", "CAN" => "421211", "EM" => "212141", "SUB" => "214121", "ESC" => "412121", "FS" => "111143", "GS" => "111341", "RS" => "131141", "US" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "CODE B" => "114131", "FNC 4" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ($X = 1; $X <= strlen($text); $X++) {
                $activeKey = substr($text, ($X - 1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum = ($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211412" . $code_string . "2331112";
        } elseif (strtolower($code_type) == "code39") {
            $code_array = array("0" => "111221211", "1" => "211211112", "2" => "112211112", "3" => "212211111", "4" => "111221112", "5" => "211221111", "6" => "112221111", "7" => "111211212", "8" => "211211211", "9" => "112211211", "A" => "211112112", "B" => "112112112", "C" => "212112111", "D" => "111122112", "E" => "211122111", "F" => "112122111", "G" => "111112212", "H" => "211112211", "I" => "112112211", "J" => "111122211", "K" => "211111122", "L" => "112111122", "M" => "212111121", "N" => "111121122", "O" => "211121121", "P" => "112121121", "Q" => "111111222", "R" => "211111221", "S" => "112111221", "T" => "111121221", "U" => "221111112", "V" => "122111112", "W" => "222111111", "X" => "121121112", "Y" => "221121111", "Z" => "122121111", "-" => "121111212", "." => "221111211", " " => "122111211", "$" => "121212111", "/" => "121211121", "+" => "121112121", "%" => "111212121", "*" => "121121211");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ($X = 1; $X <= strlen($upper_text); $X++) {
                $code_string .= $code_array[substr($upper_text, ($X - 1), 1)] . "1";
            }

            $code_string = "1211212111" . $code_string . "121121211";
        } elseif (strtolower($code_type) == "code25") {
            $code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
            $code_array2 = array("3-1-1-1-3", "1-3-1-1-3", "3-3-1-1-1", "1-1-3-1-3", "3-1-3-1-1", "1-3-3-1-1", "1-1-1-3-3", "3-1-1-3-1", "1-3-1-3-1", "1-1-3-3-1");

            for ($X = 1; $X <= strlen($text); $X++) {
                for ($Y = 0; $Y < count($code_array1); $Y++) {
                    if (substr($text, ($X - 1), 1) == $code_array1[$Y]) {
                        $temp[$X] = $code_array2[$Y];
                    }
                }
            }

            for ($X = 1; $X <= strlen($text); $X+=2) {
                if (isset($temp[$X]) && isset($temp[($X + 1)])) {
                    $temp1 = explode("-", $temp[$X]);
                    $temp2 = explode("-", $temp[($X + 1)]);
                    for ($Y = 0; $Y < count($temp1); $Y++) {
                        $code_string .= $temp1[$Y] . $temp2[$Y];
                    }
                }
            }

            $code_string = "1111" . $code_string . "311";
        } elseif (strtolower($code_type) == "codabar") {
            $code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "-", "$", ":", "/", ".", "+", "A", "B", "C", "D");
            $code_array2 = array("1111221", "1112112", "2211111", "1121121", "2111121", "1211112", "1211211", "1221111", "2112111", "1111122", "1112211", "1122111", "2111212", "2121112", "2121211", "1121212", "1122121", "1212112", "1112122", "1112221");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ($X = 1; $X <= strlen($upper_text); $X++) {
                for ($Y = 0; $Y < count($code_array1); $Y++) {
                    if (substr($upper_text, ($X - 1), 1) == $code_array1[$Y]) {
                        $code_string .= $code_array2[$Y] . "1";
                    }
                }
            }
            $code_string = "11221211" . $code_string . "1122121";
        }

// Pad the edges of the barcode
        $code_length = 20;
        for ($i = 1; $i <= strlen($code_string); $i++) {
            $code_length = $code_length + (integer) (substr($code_string, ($i - 1), 1));
        }

        if (strtolower($orientation) == "horizontal") {
            $img_width = $code_length;
            $img_height = $size;
        } else {
            $img_width = $size;
            $img_height = $code_length;
        }

        $image = imagecreate($img_width, $img_height);
        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $white);

        $location = 10;
        for ($position = 1; $position <= strlen($code_string); $position++) {
            $cur_size = $location + ( substr($code_string, ($position - 1), 1) );
            if (strtolower($orientation) == "horizontal") {
                imagefilledrectangle($image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black));
            } else {
                imagefilledrectangle($image, 0, $location, $img_width, $cur_size, ($position % 2 == 0 ? $white : $black));
            }
            $location = $cur_size;
        }
// Draw barcode to the screen
        header('Content-type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
    }
}
