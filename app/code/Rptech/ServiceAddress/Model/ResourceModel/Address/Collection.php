<?php
/**
 * @author Rptech
 * @package Rptech_ServiceAddress
 */
namespace Rptech\ServiceAddress\Model\ResourceModel\Address;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Rptech\ServiceAddress\Model\ResourceModel\Address
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'rptech_serviceaddress_address_collection';
    protected $_eventObject = 'address_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\ServiceAddress\Model\Address', 'Rptech\ServiceAddress\Model\ResourceModel\Address');
    }

}