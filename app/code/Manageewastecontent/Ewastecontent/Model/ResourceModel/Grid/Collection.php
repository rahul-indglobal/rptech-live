<?php

/**
 * Manageewastecontent_Ewastecontent Grid Collection.
 *
 * @category  Manageewastecontent_Ewastecontent 
 * @package   Manageewastecontent_Ewastecontent
 * @author    Lalita Rajput
 */
namespace Manageewastecontent\Ewastecontent\Model\ResourceModel\Grid;

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
            'Manageewastecontent\Ewastecontent\Model\Grid',
            'Manageewastecontent\Ewastecontent\Model\ResourceModel\Grid'
        );
    }
}
