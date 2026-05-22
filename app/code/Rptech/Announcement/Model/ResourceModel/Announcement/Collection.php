<?php
/**
 * @author Rptech
 * @package Rptech_Announcement
 */
namespace Rptech\Announcement\Model\ResourceModel\Announcement;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Rptech\Announcement\Model\ResourceModel\Announcement
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'rptech_announcement_collection';
    protected $_eventObject = 'announcement_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\Announcement\Model\Announcement', 'Rptech\Announcement\Model\ResourceModel\Announcement');
    }

}