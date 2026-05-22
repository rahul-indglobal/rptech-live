<?php

namespace Rptech\Event\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Rptech\Event\Api\Data\EventImageInterface;

/**
 * Class EventImage
 * @package Rptech\Event\Model
 */
class EventImage extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'rptech_event_images';

    protected $_cacheTag = 'rptech_event_images';

    protected $_eventPrefix = 'rptech_event_images';

    /**
     * Resource Model
     */
    protected function _construct()
    {
        $this->_init('Rptech\Event\Model\ResourceModel\EventImage');
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    public function getEventEntityId()
    {
        return $this->getData(EventImageInterface::KEY_EVENT_ENTITY_ID);
    }

    public function setEventEntityId(int $eventEntityId)
    {
        return $this->setData(EventImageInterface::KEY_EVENT_ENTITY_ID, $eventEntityId);
    }

    public function getImage()
    {
        return $this->getData(EventImageInterface::KEY_IMAGE);
    }

    public function setImage(string $image)
    {
        return $this->setData(EventImageInterface::KEY_IMAGE, $image);
    }

    public function getImageSortPosition()
    {
        return $this->getData(EventImageInterface::KEY_IMAGE_SORT_POSITION);
    }

    public function setImageSortPosition(int $imageSortPosition)
    {
        return $this->setData(EventImageInterface::KEY_IMAGE_SORT_POSITION, $imageSortPosition);
    }

    public function getCreatedAt()
    {
        return $this->getData(EventImageInterface::KEY_CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(EventImageInterface::KEY_CREATED_AT, $createdAt);
    }

    public function getUpdatedAt()
    {
        return $this->getData(EventImageInterface::KEY_UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(EventImageInterface::KEY_UPDATED_AT, $updatedAt);
    }
}