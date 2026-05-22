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

/**
 * Managesupplieraddrtab Purchaseorders model
 */
class Purchaseorders extends AbstractModel
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
        $this->_init('Biztech\Inventorysystem\Model\ResourceModel\Purchaseorders');
    }


    /**
     * @param Purchaseorders $object
     * @return array
     */
    public function getProducts(\Biztech\Inventorysystem\Model\Purchaseorders $object)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Purchaseorders::TBL_PO_ITEMS);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['*']
        )
            ->where(
                'purchase_order_id = ?',
                (int)$object->getId()
            );
        return $this->getResource()->getConnection()->fetchAll($select);
    }

    /**
     * @param Purchaseorders $object
     * @return array
     */
    public function getComments(\Biztech\Inventorysystem\Model\Purchaseorders $object)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Purchaseorders::TBL_PO_COMMENTS);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['*']
        )
            ->where(
                'purchaseorder_id = ?',
                (int)$object->getId()
            );
        return $this->getResource()->getConnection()->fetchAll($select);
    }


    /**
     * @param Purchaseorders $object
     * @return array
     */
    public function getStockReceivedIds(\Biztech\Inventorysystem\Model\Purchaseorders $object)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Stockreceived::TBL_SR);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['GROUP_CONCAT(stockreceived_id) AS stockreceived_id']
        )
            ->where(
                'purchaseorder_id = ?',
                $object->getPurchaseOrderId()
            );
        return $this->getResource()->getConnection()->fetchCol($select);
    }

    /**
     * TODO: Invoice IDS Filter
     * @param Purchaseorders $object
     * @return array
     */
    public function getInvoiceIds(\Biztech\Inventorysystem\Model\Purchaseorders $object)
    {
        return false;
    }
}
