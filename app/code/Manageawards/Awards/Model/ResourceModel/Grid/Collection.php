<?php

/**
 * Manageawards_Awards Grid Collection.
 *
 * @category  Manageawards_Awards 
 * @package   Manageawards_Awards
 * @author    Lalita Rajput
 */
namespace Manageawards\Awards\Model\ResourceModel\Grid;

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
            'Manageawards\Awards\Model\Grid',
            'Manageawards\Awards\Model\ResourceModel\Grid'
        );
    }
}
