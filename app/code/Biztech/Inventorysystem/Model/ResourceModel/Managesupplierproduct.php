<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Managesupplierproduct extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('bc_supplier_product_is', 'rel_id');
    }

    /**
     * Save supplier relation
     * @param  object $supplier [description]
     * @param  string $data     [description]
     * @return $this
     */
    public function saveManagesupplierRelation($supplier, $data = '')
    {
        if (!is_array($data) && is_null($data)) {
            return $this;
        }

        if ($this->isModified($supplier)) {
            $deleteCondition = $this->getConnection()->quoteInto('supplier_id=?', $supplier->getId());
            $this->getConnection()->delete($this->getMainTable(), $deleteCondition);

            foreach ($data as $productId => $info) {
                $this->getConnection()->insert($this->getMainTable(), [
                    'supplier_id' => $supplier->getId(),
                    'product_id' => $info,
                    'position' => 0
                ]);
            }
        }
        return $this;
    }
}
