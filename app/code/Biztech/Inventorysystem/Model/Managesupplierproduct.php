<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\Exception;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Managesupplierproduct extends AbstractModel
{
    /**
     * @param Context               $context
     * @param Registry              $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null       $resourceCollection
     * @param array                 $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('Biztech\Inventorysystem\Model\ResourceModel\Managesupplierproduct');
    }

    /**
     * @param $supplier
     * @return $this
     */
    public function saveManagesupplierRelation($supplier)
    {
        $data = $supplier->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveManagesupplierRelation($supplier, $data);
        }
        return $this;
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function getSuppliers($productId, $field = '')
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplier::TBL_ATT_PRODUCT);

        if ($field == '') {
            $select = $this->getResource()->getConnection()->select()->from(
                $tbl,
                '*'
            );
        } else {
            $select = $this->getResource()->getConnection()->select()->from(
                $tbl,
                $field
            );
        }

        $select->where(
            'product_id = ?',
            (int)$productId
        );

        if ($field == '') {
            return $this->getResource()->getConnection()->fetchAll($select);
        } else {
            return $this->getResource()->getConnection()->fetchCol($select);
        }
    }

    /**
     * @param $supplierID
     * @param $productID
     * @return mixed
     */
    public function getSupplierProduct($supplierID, $productID)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplier::TBL_ATT_PRODUCT);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            '*'
        )
            ->where(
                'product_id = ?',
                (int)$productID
            )
            ->where(
                'supplier_id = ?',
                (int)$supplierID
            );
        return $this->getResource()->getConnection()->fetchAll($select);
    }


    /**
     * @param $supplier
     * @return mixed
     */
    public function getProductCollection($supplier)
    {
        $collection = $this->getCollection()->addFieldToFilter('supplier_id', $supplier->getSupplierId());
        return $collection;
    }
}
