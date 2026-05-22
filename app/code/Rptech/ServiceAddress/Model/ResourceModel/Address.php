<?php
/**
 * @author Rptech
 * @package Rptech_ServiceAddress
 */
namespace Rptech\ServiceAddress\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Address
 * @package Rptech\ServiceAddress\Model\ResourceModel
 */
class Address extends AbstractDb
{
    /**
     * Address constructor.
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
        $this->_init('services_address', 'entity_id');
    }

}