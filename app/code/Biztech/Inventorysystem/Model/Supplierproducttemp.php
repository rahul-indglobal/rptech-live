<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\Collection\Db;
use Magento\Framework\Exception\Exception;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Supplierproducttemp extends AbstractModel
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
        $this->_init('Biztech\Inventorysystem\Model\ResourceModel\Supplierproducttemp');
    }

    /**
     * @param $supplierID
     * @param $productID
     * @return mixed
     */
    public function getSupplierProduct($supplierID, $productID)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Supplierproducttemp::TBL_SUP_PRODUCT_APPROVE);
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
     * @param $productID
     * @return mixed
     */
    public function getSuppliers($productID)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Supplierproducttemp::TBL_SUP_PRODUCT_APPROVE);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            '*'
        )
            ->where(
                'product_id = ?',
                (int)$productID
            );

        return $this->getResource()->getConnection()->fetchAll($select);
    }
}
