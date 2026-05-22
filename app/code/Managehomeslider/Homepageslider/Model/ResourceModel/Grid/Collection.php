<?php

/**
 * Grid Grid Collection.
 *
 * @category  Managehomeslider_Homepageslider 
 * @package   Managehomeslider_Homepageslider
 * @author    Lalita Rajput
 */
namespace Managehomeslider\Homepageslider\Model\ResourceModel\Grid;

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
            'Managehomeslider\Homepageslider\Model\Grid',
            'Managehomeslider\Homepageslider\Model\ResourceModel\Grid'
        );
    }
}
