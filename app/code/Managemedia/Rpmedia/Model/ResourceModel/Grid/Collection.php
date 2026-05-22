<?php

/**
 * Grid Grid Collection.
 *
 * @category  Managemedia_Rpmedia 
 * @package   Managemedia_Rpmedia
 * @author    Lalita Rajput
 */
namespace Managemedia\Rpmedia\Model\ResourceModel\Grid;

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
            'Managemedia\Rpmedia\Model\Grid',
            'Managemedia\Rpmedia\Model\ResourceModel\Grid'
        );
    }
}
