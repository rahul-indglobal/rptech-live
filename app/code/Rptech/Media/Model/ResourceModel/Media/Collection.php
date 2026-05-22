<?php
namespace Rptech\Media\Model\ResourceModel\Media;
/**
 * Class Collection
 * @package Rptech\Media\Model\ResourceModel\Media
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'rptech_media_collection';
    protected $_eventObject = 'media_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\Media\Model\Media', 'Rptech\Media\Model\ResourceModel\Media');
    }

}