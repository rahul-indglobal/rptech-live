<?php

/**
 * Grid Grid Collection.
 *
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace LalitaRajput\B2B\Model\ResourceModel\Grid;

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
            'LalitaRajput\B2B\Model\Grid',
            'LalitaRajput\B2B\Model\ResourceModel\Grid'
        );
    }
    
     protected function _initSelect()
    {
        parent::_initSelect();
//        $this->getSelect()->joinLeft(
//            ['secondTable' => $this->getTable('sales_order_item')], //2nd table name by which you want to join mail table
//            'main_table.entity_id = secondTable.order_id', // common column which available in both table 
//            '*' // '*' define that you want all column of 2nd table. if you want some particular column then you can define as ['column1','column2']
//        )->where("customer_group = ?", 4);
    }
}
