<?php

namespace Rptech\Event\Model;

use Magento\Framework\Model\AbstractModel;
use Rptech\Event\Api\Data\EventInterface;
use Rptech\Event\Model\ResourceModel\Event as EventResourceModel;

/**
 * Class Event
 * @package Rptech\Event\Model
 */
class Event extends AbstractModel implements EventInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = EventInterface::TABLE_NAME;

    public function _construct()
    {
        $this->_init(EventResourceModel::class);
    }

    /**
     * Get cache identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array|mixed|string|null
     */
    public function getTitle()
    {
        return $this->getData(EventInterface::KEY_TITLE);
    }

    /**
     * @param string $title
     * @return EventInterface|Event
     */
    public function setTitle($title)
    {
        return $this->setData(EventInterface::KEY_TITLE, $title);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getDescription()
    {
        return $this->getData(EventInterface::KEY_DESCRIPTION);
    }

    /**
     * @param string $description
     * @return EventInterface|Event
     */
    public function setDescription($description)
    {
        return $this->setData(EventInterface::KEY_DESCRIPTION, $description);
    }

    /**
     * @return array|int|mixed|null
     */
    public function getSortPosition()
    {
        return $this->getData(EventInterface::KEY_SORT_POSITION);
    }

    /**
     * @param int $sortPosition
     * @return EventInterface|Event
     */
    public function setSortPosition(int $sortPosition)
    {
        return $this->setData(EventInterface::KEY_SORT_POSITION, $sortPosition);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getCreatedAt()
    {
        return $this->getData(EventInterface::KEY_CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return EventInterface|Event
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(EventInterface::KEY_CREATED_AT, $createdAt);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData(EventInterface::KEY_UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return EventInterface|Event
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(EventInterface::KEY_UPDATED_AT, $updatedAt);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getCity()
    {
        return $this->getData(EventInterface::KEY_CITY);
    }

    /**
     * @param string $city
     * @return EventInterface|Event
     */
    public function setCity(string $city)
    {
        return $this->setData(EventInterface::KEY_CITY, $city);
    }

    /**
     * @return array|bool|mixed|null
     */
    public function getIsActive()
    {
        return $this->getData(EventInterface::KEY_IS_ACTIVE);
    }

    /**
     * @param bool $isActive
     * @return EventInterface|Event
     */
    public function setIsActive(bool $isActive)
    {
        return $this->setData(EventInterface::KEY_IS_ACTIVE, $isActive);
    }
}