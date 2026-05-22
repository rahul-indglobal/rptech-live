<?php
/**
 * @author Rptech
 * @package Rptech_HomepageSlider
 */
namespace Rptech\HomepageSlider\Model\ResourceModel\HomepageSlider;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Rptech\HomepageSlider\Model\ResourceModel\HomepageSlider
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'rptech_homepageslider_collection';
    protected $_eventObject = 'homepageslider_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\HomepageSlider\Model\HomepageSlider', 'Rptech\HomepageSlider\Model\ResourceModel\HomepageSlider');
    }

}