<?php
/**
 * @author Rptech
 * @package Rptech_Brand
 */
namespace Rptech\Brand\Model\ResourceModel\Brand;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Rptech\Brand\Model\ResourceModel\Brand
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'rptech_brand_collection';
    protected $_eventObject = 'brand_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\Brand\Model\Brand', 'Rptech\Brand\Model\ResourceModel\Brand');
    }

}