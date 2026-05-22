<?php
/**
 * @author Rptech
 * @package Rptech_Awards
 */
namespace Rptech\Awards\Model\ResourceModel\Awards;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Rptech\Awards\Model\ResourceModel\Awards
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'rptech_award_collection';
    protected $_eventObject = 'award_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\Awards\Model\Awards', 'Rptech\Awards\Model\ResourceModel\Awards');
    }

}