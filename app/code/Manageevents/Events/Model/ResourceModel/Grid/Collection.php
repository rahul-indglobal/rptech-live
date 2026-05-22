<?php

/**
 * Grid Grid Collection.
 *
 * @category  Manageevents_Events 
 * @package   Manageevents_Events
 * @author    Lalita Rajput
 */
namespace Manageevents\Events\Model\ResourceModel\Grid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            'Manageevents\Events\Model\Grid',
            'Manageevents\Events\Model\ResourceModel\Grid'
        );
    }
}
