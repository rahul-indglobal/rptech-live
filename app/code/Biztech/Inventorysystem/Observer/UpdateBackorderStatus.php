<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class UpdateBackorderStatus implements ObserverInterface
{
    protected $scopeConfig;
    protected $_stockRegistry;

    /**
     * @param ScopeConfigInterface                                      $scopeConfig
     * @param \Magento\Framework\App\ResourceConnection                 $resourceConnection
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockitemRepository
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_resourceConnection = $resourceConnection;
        $this->_stockRegistry = $stockRegistry;
    }

    /**
     * This function is used for the update the back order
     * @param  Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $getEvnt = $observer->getEvent();
        $event = $getEvnt->getName();

        $orderID=$observer->getEvent()->getOrderIds();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $getOrder = $objectManager->create('\Magento\Sales\Model\Order')->load($orderID[0]);

        switch ($event) {
            case "checkout_onepage_controller_success_action":
                //$getOrder = $observer->getOrder();
                $isBackordered = 0;
                $this->_resources = $this->_resourceConnection;
                $connection = $this->_resources->getConnection();

                $tblName = $this->_resources->getTableName('sales_order');
                
                // Set default store wise (Warehouse) for the update qty on that storeview (Warehouse)

                $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $getOrder->getStoreId());
                
                // Code coplete here for multiwebsite.
                
                $min_warehouse_qty = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/warehouse_level_quantity', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

                foreach ($getOrder->getAllItems() as $item) {
                    if ($item->getWarehouseId() == null) {
                        $item->setWarehouseId($defaultWarehouseID);
                        $item->save();
                    }

                    $qtyOrdered = $item->getQtyOrdered();
                    $prodQty = $this->_stockRegistry->getStockItem($item->getProductId())->getQty();

                    $getCurWareProdQtySql = "SELECT * FROM " . $this->_resources->getTableName('bc_warehouse_product_is') . " WHERE product_id = " . $item->getProductId();
                    
                    $getCurWareProdQtyFetch = $connection->fetchAll($getCurWareProdQtySql);

                    foreach ($getCurWareProdQtyFetch as $key => $data) {
                        if ($data['quantity'] < $min_warehouse_qty) {
                            $isBackordered = 1;
                        }
                    }
                }
                if ($isBackordered == 1) {
                    $connection->update($tblName, array("bc_backordered" => 1), "entity_id=" . $getOrder->getEntityId());
                } else {
                    $connection->update($tblName, array("bc_backordered" => 0), "entity_id=" . $getOrder->getEntityId());
                }
                break;
            case "multishipping_checkout_controller_success_action":
                //$getOrder = $observer->getOrder();
                $isBackordered = 0;
                $this->_resources = $this->_resourceConnection;
                $connection = $this->_resources->getConnection();

                $tblName = $this->_resources->getTableName('sales_order');
                
                // Set default store wise (Warehouse) for the update qty on that storeview (Warehouse)

                $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $getOrder->getStoreId());
                
                // Code coplete here for multiwebsite.
                
                $min_warehouse_qty = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/warehouse_level_quantity', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

                foreach ($getOrder->getAllItems() as $item) {
                    if ($item->getWarehouseId() == null) {
                        $item->setWarehouseId($defaultWarehouseID);
                        $item->save();
                    }

                    $qtyOrdered = $item->getQtyOrdered();
                    $prodQty = $this->_stockRegistry->getStockItem($item->getProductId())->getQty();

                    $getCurWareProdQtySql = "SELECT * FROM " . $this->_resources->getTableName('bc_warehouse_product_is') . " WHERE product_id = " . $item->getProductId();
                    
                    $getCurWareProdQtyFetch = $connection->fetchAll($getCurWareProdQtySql);

                    foreach ($getCurWareProdQtyFetch as $key => $data) {
                        if ($data['quantity'] < $min_warehouse_qty) {
                            $isBackordered = 1;
                        }
                    }
                }
                if ($isBackordered == 1) {
                    $connection->update($tblName, array("bc_backordered" => 1), "entity_id=" . $getOrder->getEntityId());
                } else {
                    $connection->update($tblName, array("bc_backordered" => 0), "entity_id=" . $getOrder->getEntityId());
                }
                break;
        }
    }
}
