<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model;

use Magento\Framework\Exception\WarehouseException;

class Warehouse extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\Db $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('Biztech\Inventorysystemadvance\Model\ResourceModel\Warehouse');
    }

    /**
     * Warehouse name
     * @return Array
     */
    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->warehouseFactory = $objectManager->create('Biztech\Inventorysystemadvance\Model\Warehouse')->getCollection();
        $getData = $this->warehouseFactory->addFieldToFilter('status', 1)->getData();
        for ($i = 0; $i < count($getData); $i++) {
            $options[] = array('value' => $getData[$i]['id'], 'label' => $getData[$i]['warehouse_name']);
        }
        return $options;
    }

    /**
     * Warehouse products
     * @param  \Biztech\Inventorysystemadvance\Model\Warehouse $object
     * @return object
     */
    public function getProducts(\Biztech\Inventorysystemadvance\Model\Warehouse $object)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->create('\Magento\Framework\App\ResourceConnection');
        $tbl = $connection->getTableName('bc_warehouse_product_is');
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['product_id']
        )
                ->where(
                    'warehouse_id = ?',
                    (int) $object->getId()
                );
        return $this->getResource()->getConnection()->fetchCol($select);
    }

    /**
     * Warehouse options
     * @return Array
     */
    public function getAllOptions()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->warehouseFactory = $objectManager->create('Biztech\Inventorysystemadvance\Model\Warehouse')->getCollection();
        $getData = $this->warehouseFactory->addFieldToFilter('status', 1)->getData();
        for ($i = 0; $i < count($getData); $i++) {
            $options[] = array('value' => $getData[$i]['id'], 'label' => $getData[$i]['warehouse_name']);
        }
        array_unshift($options, array('value' => '', 'label' => ''));
        return $options;
    }

    /**
     * Warehouse options
     * @return Array
     */
    public function getAllOption()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->warehouseFactory = $objectManager->create('Biztech\Inventorysystemadvance\Model\Warehouse')->getCollection();
        $getData = $this->warehouseFactory->addFieldToFilter('status', 1)->getData();
        for ($i = 0; $i < count($getData); $i++) {
            $options[$getData[$i]['id']] = $getData[$i]['warehouse_name'];
        }
        return $options;
    }
    
    /**
     * Warehouse products details
     * @param  $productId
     * @return $this
     */
    public function getDetailsFromProduct($productId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->create('\Magento\Framework\App\ResourceConnection');
        $tbl = $connection->getTableName('bc_warehouse_product_is');
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['*']
        )
               ->where(
                   'product_id = ?',
                   (int) $productId
               );
        return $this->getResource()->getConnection()->fetchAll($select);
    }

    /**
     * Warehouse products
     * @param  $productId
     * @return $this
     */
    public function getWarehouseFromProduct($productId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->create('\Magento\Framework\App\ResourceConnection');
        $tbl = $connection->getTableName('bc_warehouse_product_is');
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['warehouse_id']
        )
                ->where(
                    'product_id = ?',
                    (int) $productId
                );
        return $this->getResource()->getConnection()->fetchAll($select);
    }
}
