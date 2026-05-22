<?php

/**
 * Grid Grid Collection.
 *
 * @category  Leaders 
 * @package   Manageleaders_Leaders
 * @author    Lalita Rajput
 */
namespace Manageleaders\Leaders\Model\ResourceModel\Grid;

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
            'Manageleaders\Leaders\Model\Grid',
            'Manageleaders\Leaders\Model\ResourceModel\Grid'
        );
    }
}
