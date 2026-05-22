<?php

namespace Lalita\Mysmsmodule\Model;

class Enquiry extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Lalita\Mysmsmodule\Model\ResourceModel\Enquiry::class);
    }
}