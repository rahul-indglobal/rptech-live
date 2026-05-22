<?php

namespace Rptech\StockUpdate\Model\ResourceModel\Stock;

use Magento\Framework\Exception\NoSuchEntityException;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\Rptech\StockUpdate\Model\Stock::class, \Rptech\StockUpdate\Model\ResourceModel\Stock::class);
    }
}