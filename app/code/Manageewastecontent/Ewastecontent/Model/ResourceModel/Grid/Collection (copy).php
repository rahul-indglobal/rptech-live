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
    
    protected function _initSelect()
    {
        parent::_initSelect();
 
        $this->getSelect()->joinLeft(
            ['secondTable' => $this->getTable('ves_brand')], //2nd table name by which you want to join mail table
            'main_table.brand_id = secondTable.brand_id', // common column which available in both table 
            'secondTable.name as brand_name' // '*' define that you want all column of 2nd table. if you want some particular column then you can define as ['column1','column2']
        );
    }
}
