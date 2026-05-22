<?php
/**
 * @author Rptech
 * @package Rptech_Brand
 */
namespace Rptech\Brand\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Brand
 * @package Rptech\Brand\Model\ResourceModel
 */
class Brand extends AbstractDb
{
    /**
     * Brand constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('brand', 'entity_id');
    }

}