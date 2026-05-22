<?php

namespace Lalita\Mysmsmodule\Model\ResourceModel;

class Enquiry extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('rptech_enquiry_form', 'id');
    }
}