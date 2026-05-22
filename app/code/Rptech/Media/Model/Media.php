<?php

namespace Rptech\Media\Model;
/**
 * Class Media
 * @package Rptech\Media\Model
 */
class Media extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'rptech_media';

    protected $_cacheTag = 'rptech_media';

    protected $_eventPrefix = 'rptech_media';

    protected function _construct()
    {
        $this->_init('Rptech\Media\Model\ResourceModel\Media');
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