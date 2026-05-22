<?php

/**
 * Grid GridInterface.
 * @category  Manageevents_Events 
 * @package   Manageevents_Events
 * @author    Lalita Rajput
 */

namespace Manageevents\Events\Api\Data;

interface GridInterface {

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const TITLE = 'title';
    const CONTENT = 'content';
    const CITY = 'city';
    const EVENT_IMAGE_1 = 'event_image_1';
    const EVENT_IMAGE_2 = 'event_image_2';
    const EVENT_IMAGE_3 = 'event_image_3';
    const EVENT_IMAGE_4 = 'event_image_4';
    const EVENT_IMAGE_5 = 'event_image_5';
    const EVENT_IMAGE_6 = 'event_image_6';
    const EVENT_IMAGE_7 = 'event_image_7';
    const EVENT_IMAGE_8 = 'event_image_8';
    const EVENT_IMAGE_9 = 'event_image_9';
    const EVENT_IMAGE_10 = 'event_image_10';
    const PUBLISH_DATE = 'publish_date';
    const IS_ACTIVE = 'is_active';
    const UPDATE_TIME = 'update_time';
    const CREATED_AT = 'created_at';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId);

    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getTitle();

    /**
     * Set Title.
     */
    public function setTitle($title);

    /**
     * Get Content.
     *
     * @return varchar
     */
    public function getContent();

    /**
     * Set Content.
     */
    public function setContent($content);

    public function getCity();

    public function setCity($city);

    public function getEventimage1();

    public function setEventimage1($eventimage1);

    public function getEventimage2();

    public function setEventimage2($eventimage2);

    public function getEventimage3();

    public function setEventimage3($eventimage3);

    public function getEventimage4();

    public function setEventimage4($eventimage4);

    public function getEventimage5();

    public function setEventimage5($eventimage5);

    public function getEventimage6();

    public function setEventimage6($eventimage6);

    public function getEventimage7();

    public function setEventimage7($eventimage7);

    public function getEventimage8();

    public function setEventimage8($eventimage8);

    public function getEventimage9();

    public function setEventimage9($eventimage9);

    public function getEventimage10();

    public function setEventimage10($eventimage10);

    /**
     * Get Publish Date.
     *
     * @return varchar
     */
    public function getPublishDate();

    /**
     * Set PublishDate.
     */
    public function setPublishDate($publishDate);

    /**
     * Get IsActive.
     *
     * @return varchar
     */
    public function getIsActive();

    /**
     * Set StartingPrice.
     */
    public function setIsActive($isActive);

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getUpdateTime();

    /**
     * Set UpdateTime.
     */
    public function setUpdateTime($updateTime);

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt();

    /**
     * Set CreatedAt.
     */
    public function setCreatedAt($createdAt);
}
