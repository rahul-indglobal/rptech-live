<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model\ResourceModel;

class Warehouse extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected $_date;
    /**
     * Initialize resource
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialization
     * @return void
     */
    protected function _construct()
    {
        $this->_init('bc_warehouses_is', 'id');
    }

    /**
     * Process post data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreationTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @throws Exception
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $defaultWarehouseQty = 0;
        $relID = 0;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_bizhelper = $objectManager->create('Biztech\Inventorysystem\Helper\Data');
        $defaultWarehouseId = $_bizhelper->getConfig('inventorysystem/inventorysystemadvance/default_warehouse_select');

        if ($defaultWarehouseId && $object->getId() == $defaultWarehouseId) {
            throw new \Exception(__('Please select another default warehouse from system config before deleting this warehouse.'), 1);
        } else {
            $getCurrentWarehouseData = $this->getProducts($object);

            if (isset($getCurrentWarehouseData[0]) && !empty($getCurrentWarehouseData[0])) {
                foreach ($getCurrentWarehouseData as $key => $currentwarehouseData) {
                    $getdefaultWarehouseData = $this->getDefaultWareHouseData($defaultWarehouseId, $currentwarehouseData['product_id']);

                    if (isset($getdefaultWarehouseData[0]) && !empty($getdefaultWarehouseData[0])) {
                        $defaultWarehouseQty = (int)$getdefaultWarehouseData[0]['quantity'] + (int)$currentwarehouseData['quantity'];
                        $relID = $getdefaultWarehouseData[0]['rel_id'];
                    } else {
                        $defaultWarehouseQty = (int)$currentwarehouseData['quantity'];
                    }

                    $_warehouseProductModelFactory = $objectManager->create('Biztech\Inventorysystemadvance\Model\WarehouseproductFactory');
                    $_warehouseProductModel = $_warehouseProductModelFactory->create();

                    if ($relID) {
                        $_warehouseProductModel->setId($relID);
                    }
                    $_warehouseProductModel->setWarehouseId($defaultWarehouseId);
                    $_warehouseProductModel->setProductId($currentwarehouseData['product_id']);
                    $_warehouseProductModel->setPosition(0);
                    $_warehouseProductModel->setQuantity($defaultWarehouseQty);
                    $_warehouseProductModel->save();

                    $_currentWarehouseModel = $_warehouseProductModelFactory->create();
                    $_currentWarehouseModel->setId($currentwarehouseData['rel_id'])->delete();
                }
            }
            /*$_warehouseModel = $objectManager->create('Biztech\Inventorysystemadvance\Model\WarehouseFactory');
            $_warehouseModel->setId($object->getId())->delete();*/
        }
        return parent::_beforeDelete($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return array
     */
    public function getProducts(\Magento\Framework\Model\AbstractModel $object)
    {
        $tbl = $this->getTable('bc_warehouse_product_is');
        $select = $this->getConnection()->select()->from(
            $tbl,
            ['*']
        )
            ->where(
                'warehouse_id = ?',
                (int)$object->getId()
            );
        return $this->getConnection()->fetchAll($select);
    }

    /**
     * @param $defaultWHID
     * @param $productId
     * @return array
     */
    public function getDefaultWareHouseData($defaultWHID, $productId)
    {
        $tbl = $this->getTable('bc_warehouse_product_is');
        $select = $this->getConnection()->select()->from(
            $tbl,
            ['*']
        )
            ->where(
                'warehouse_id = ?',
                (int)$defaultWHID
            )->where(
                'product_id = ?',
                (int)$productId
            );
        return $this->getConnection()->fetchAll($select);
    }
}
