<?php

namespace Rptech\Event\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Rptech\Event\Api\Data\EventInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Event
 * @package Rptech\Event\Model\ResourceModel
 */
class Event extends AbstractDb
{
    /**
     * Event constructor.
     * @param Context $context
     * @param null $connectionName
     */
    public function __construct(Context $context, $connectionName = null)
    {
        parent::__construct($context, $connectionName);
    }

    public function _construct()
    {
        $this->_init(EventInterface::TABLE_NAME, EventInterface::KEY_ENTITY_ID);
    }
}