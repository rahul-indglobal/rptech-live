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

class Stockreceived extends AbstractModel
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
        $this->_init('Biztech\Inventorysystem\Model\ResourceModel\Stockreceived');
    }

    /**
     * Get product of stock recevied
     * @param  \Biztech\Inventorysystem\Model\Stockreceived $object
     * @return object
     */
    public function getProducts(\Biztech\Inventorysystem\Model\Stockreceived $object)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Stockreceiveditems::TBL_SR_ITEMS);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['*']
        )
            ->where(
                'stockreceived_id = ?',
                (int)$object->getId()
            );
        return $this->getResource()->getConnection()->fetchAll($select);
    }
}
