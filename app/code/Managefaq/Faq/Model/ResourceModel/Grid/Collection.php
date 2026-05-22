<?php

/**
 * Grid Grid Collection.
 *
 * @category  Leaders 
 * @package   Managefaq_Faq
 * @author    Lalita Rajput
 */
namespace Managefaq\Faq\Model\ResourceModel\Grid;

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
            'Managefaq\Faq\Model\Grid',
            'Managefaq\Faq\Model\ResourceModel\Grid'
        );
    }
}
