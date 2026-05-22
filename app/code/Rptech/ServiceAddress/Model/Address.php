<?php
/**
 * @author Rptech
 * @package Rptech_ServiceAddress
 */
namespace Rptech\ServiceAddress\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Class Address
 * @package Rptech\ServiceAddress\Model
 */
class Address extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'services_address';

    protected $_cacheTag = 'services_address';

    protected $_eventPrefix = 'services_address';

    protected function _construct()
    {
        $this->_init('\Rptech\ServiceAddress\Model\ResourceModel\Address');
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