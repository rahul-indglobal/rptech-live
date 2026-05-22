<?php

/**
 * Manageaboutus_Aboutus Grid Collection.
 *
 * @category  Manageaboutus_Aboutus 
 * @package   Manageaboutus_Aboutus
 * @author    Lalita Rajput
 */
namespace Manageaboutus\Aboutus\Model\ResourceModel\Grid;

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
            'Manageaboutus\Aboutus\Model\Grid',
            'Manageaboutus\Aboutus\Model\ResourceModel\Grid'
        );
    }
}
