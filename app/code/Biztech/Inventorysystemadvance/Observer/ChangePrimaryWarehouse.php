<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Observer;

use Biztech\Inventorysystem\Helper\Data as BizInventoryHelper;
use Biztech\Inventorysystemadvance\Model\Managehistory\Actiontype;
use Biztech\Inventorysystemadvance\Model\Managehistory\Systemaction;
use Biztech\Inventorysystemadvance\Model\Managehistory\SystemInterface;
use Biztech\Inventorysystemadvance\Model\WarehouseproductFactory;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ChangePrimaryWarehouse implements ObserverInterface
{

    protected $scopeConfig;
    protected $_warehouseModel;
    protected $_resourceConnection;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse    $warehouseModel
     * @param \Magento\Framework\App\ResourceConnection          $resourceConnection
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_warehouseModel = $warehouseModel;
        $this->_resourceConnection = $resourceConnection;
    }

    /**
     * @param Observer $observer
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        
        $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->_resources = $this->_resourceConnection;
        $connection = $this->_resources->getConnection();
        $saveDefWarModel = $this->_warehouseModel;
        $getPrimaryWarehouse = $saveDefWarModel->load($defaultWarehouseID)->getPrimaryWarehouse();
        if (!$getPrimaryWarehouse) {
            /* set value of primary warehouse of all rows to 0 */
            $tableName = $connection->getTableName('bc_warehouses_is');
            $updateSql = "UPDATE " . $tableName . " SET primary_warehouse = 0";
            $connection->query($updateSql);

            /* set new default warehouse */
            
            $saveDefWarModel->setId($defaultWarehouseID);
            $saveDefWarModel->setPrimaryWarehouse(1);
            $saveDefWarModel->save();
        }
    }
}
