<?php

/**
 * Grid Grid Collection.
 *
 * @category  Branchheads 
 * @package   Managebranchheads_Branchheads
 * @author    Lalita Rajput
 */
namespace Managebranchheads\Branchheads\Model\ResourceModel\Grid;

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
            'Managebranchheads\Branchheads\Model\Grid',
            'Managebranchheads\Branchheads\Model\ResourceModel\Grid'
        );
    }
}
