<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystemadvance\Model;

use Magento\Framework\Exception\WarehouseException;

class Warehouseproduct extends \Magento\Framework\Model\AbstractModel
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
        $this->_init('Biztech\Inventorysystemadvance\Model\ResourceModel\Warehouseproduct');
    }

    /**
     * Save warehouse relations
     * @param  $warehouse
     * @return $this
     */
    public function saveWarehouseRelation($warehouse)
    {
        $data = $warehouse->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveWarehouseRelation($warehouse, $data);
        }
        return $this;
    }
}
