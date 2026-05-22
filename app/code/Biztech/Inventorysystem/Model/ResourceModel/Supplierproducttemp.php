<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Managesupplieraddr resource
 */
class Supplierproducttemp extends AbstractDb
{
    const TBL_SUP_PRODUCT_APPROVE = 'bc_supplier_product_approve_is';

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(self::TBL_SUP_PRODUCT_APPROVE, 'id');
    }
}
