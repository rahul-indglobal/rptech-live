<?php

namespace Rptech\StockUpdate\Model\ResourceModel;

class Stock extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }
    
    protected function _construct()
    {
        $this->_init('rptech_auto_stock_update', 'row_id');
    }
}