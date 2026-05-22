<?php

namespace Rptech\Event\Api\Data;

/**
 * Interface EventInterface
 * @package Rptech\Event\Api\Data
 */
interface EventInterface
{
    const TABLE_NAME = "event";

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const KEY_ENTITY_ID = 'entity_id';
    const KEY_TITLE = 'title';
    const KEY_DESCRIPTION = 'description';
    const KEY_SORT_POSITION = 'sort_position';
    const KEY_CITY = 'city';
    const KEY_IS_ACTIVE = 'is_active';
    const KEY_CREATED_AT = 'created_at';
    const KEY_UPDATED_AT = 'updated_at';

    /**
     * Get ID
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set ID
     *
     * @param int $entityId
     * @return EventInterface
     */
    public function setEntityId($entityId);

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set Title
     *
     * @param string $title
     * @return EventInterface
     *
     */
    public function setTitle($title);

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set Description
     *
     * @param string $description
     * @return EventInterface
     */
    public function setDescription($description);

    /**
     * Get Sort Position
     *
     * @return int
     */
    public function getSortPosition();

    /**
     * Set Image
     *
     * @param int $sortPosition
     * @return EventInterface
     */
    public function setSortPosition(int $sortPosition);

    /**
     * @return string
     */
    public function getCity();

    /**
     * @param string $city
     * @return EventInterface
     */
    public function setCity(string $city);

    /**
     * @return boolean
     */
    public function getIsActive();

    /**
     * @param boolean $isActive
     * @return EventInterface
     */
    public function setIsActive(bool $isActive);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * set created at
     *
     * @param string $createdAt
     * @return EventInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * set updated at
     *
     * @param string $updatedAt
     * @return EventInterface
     */
    public function setUpdatedAt($updatedAt);

}