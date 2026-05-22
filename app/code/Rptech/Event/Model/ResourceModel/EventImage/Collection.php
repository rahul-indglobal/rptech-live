<?php
/**
 * @author Rptech
 * @package Rptech_Event
 */
namespace Rptech\Event\Model\ResourceModel\EventImage;

use Rptech\Event\Model\EventImage as EventImageModel;
use Rptech\Event\Model\ResourceModel\EventImage as EventImageResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Rptech\Event\Api\Data\EventImageInterface;


/**
 * Class Collection
 * @package Rptech\Event\Model\ResourceModel\EventImage
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = EventImageInterface::KEY_ENTITY_ID;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(EventImageModel::class, EventImageResourceModel::class);
    }

}