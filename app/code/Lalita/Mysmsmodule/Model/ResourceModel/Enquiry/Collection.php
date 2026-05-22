<?php

namespace Lalita\Mysmsmodule\Model\ResourceModel\Enquiry;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\Lalita\Mysmsmodule\Model\Enquiry::class, \Lalita\Mysmsmodule\Model\ResourceModel\Enquiry::class);
    }
}