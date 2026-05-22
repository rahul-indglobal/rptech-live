<?php

/**
 * Grid GridInterface.
 * @category  Managehomeslider_Homepageslider 
 * @package   Managehomeslider_Homepageslider
 * @author    Lalita Rajput
 */

namespace Managehomeslider\Homepageslider\Api\Data;

interface GridInterface {

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const TITLE = 'title';
    const CONTENT = 'content';
    const SLIDERPATH = 'slider_path';
    const SLIDER_LINK = 'slider_link';
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

    public function getHomeslider();

    /**
     * Set Content.
     */
    public function setHomeslider($sliderpath);
    
    
    public function getSliderLink();
    public function setSliderLink($sliderlink);
    
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
