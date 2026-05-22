<?php

namespace Rptech\Event\Api\Data;
/**
 * Interface EventImageInterface
 * @package Rptech\Event\Api\Data
 */
interface EventImageInterface
{
    const TABLE_NAME = "event_images";

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const KEY_ENTITY_ID = 'entity_id';
    const KEY_EVENT_ENTITY_ID = 'event_entity_id';
    const KEY_IMAGE = 'image';
    const KEY_IMAGE_SORT_POSITION = 'image_sort_position';
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
     * @return EventImageInterface
     */
    public function setEntityId(int $entityId);

    /**
     * Get EventEntityId
     *
     * @return string
     */
    public function getEventEntityId();

    /**
     * Set Event Entity Id
     *
     * @param int $eventEntityId
     * @return EventImageInterface
     *
     */
    public function setEventEntityId(int $eventEntityId);

    /**
     * Get Image
     *
     * @return string
     */
    public function getImage();

    /**
     * Set Image
     *
     * @param string $image
     * @return EventImageInterface
     */
    public function setImage(string $image);

    /**
     * Get Image Sort Position
     *
     * @return int
     */
    public function getImageSortPosition();

    /**
     * Set Image Sort Position
     *
     * @param int $imageSortPosition
     * @return EventImageInterface
     */
    public function setImageSortPosition(int $imageSortPosition);

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
     * @return EventImageInterface
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
     * @return EventImageInterface
     */
    public function setUpdatedAt($updatedAt);
}