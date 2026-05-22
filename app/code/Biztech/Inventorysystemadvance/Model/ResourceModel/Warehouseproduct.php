<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model\ResourceModel;

class Warehouseproduct extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected $_date;

    /**
     * Initialization
     * @return void
     */
    protected function _construct()
    {
        $this->_init('bc_warehouse_product_is', 'rel_id');
    }
}
