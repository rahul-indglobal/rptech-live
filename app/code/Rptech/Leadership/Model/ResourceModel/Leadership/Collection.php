<?php
/**
 * @author Rptech
 * @package Rptech_Leadership
 */
namespace Rptech\Leadership\Model\ResourceModel\Leadership;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Rptech\Leadership\Model\ResourceModel\Leadership
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'rptech_leadership_collection';
    protected $_eventObject = 'leadership_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\Leadership\Model\Leadership', 'Rptech\Leadership\Model\ResourceModel\Leadership');
    }

}