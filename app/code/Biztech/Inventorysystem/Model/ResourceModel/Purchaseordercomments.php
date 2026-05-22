<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Purchaseordercomments extends AbstractDb
{
    protected $_date;

    /**
     * @param Context  $context
     * @param DateTime $date
     * @param [type]   $resourcePrefix
     */
    public function __construct(
        Context $context,
        DateTime $date
    ) {
        parent::__construct($context);
        $this->_date = $date;
    }

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('bc_purchaseorder_comments_is', 'id');
    }

    /**
     * Save PO comments
     * @param  \Magento\Framework\Model\AbstractModel $object
     * @return object
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreatedAt($this->_date->gmtDate());
        }

        $object->setUpdatedAt($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }
}
