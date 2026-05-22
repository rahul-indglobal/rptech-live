<?php

namespace Rptech\StockUpdate\Model;

use Magento\Store\Model\ScopeInterface;

class Stock extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'rptech_auto_stock_update';
    const UPDATE_STATUS_PENDING = 1;
    const UPDATE_STATUS_UPDATED = 2;
    const UPDATE_STATUS_ERROR = 3;

    protected $_cacheTag = 'rptech_auto_stock_update';

    protected $_eventPrefix = 'rptech_auto_stock_update';
    
    protected function _construct()
    {
        $this->_init(\Rptech\StockUpdate\Model\ResourceModel\Stock::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}