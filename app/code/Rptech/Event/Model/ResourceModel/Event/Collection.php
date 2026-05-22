<?php
/**
 * @author Rptech
 * @package Rptech_Event
 */
namespace Rptech\Event\Model\ResourceModel\Event;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Rptech\Event\Api\Data\EventInterface;
use Rptech\Event\Model\Event as EventModel;
use Rptech\Event\Model\ResourceModel\Event as EventResourceModel;

/**
 * Class Collection
 * @package Rptech\Event\Model\ResourceModel\Event
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = EventInterface::KEY_ENTITY_ID;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(EventModel::class, EventResourceModel::class);
    }

}