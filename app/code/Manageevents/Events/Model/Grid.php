<?php

/**
 * Grid Grid Model.
 * @category  Manageevents_Events 
 * @package   Manageevents_Events
 * @author    Lalita Rajput
 */

namespace Manageevents\Events\Model;

use Manageevents\Events\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface {

    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'rptech_manageEvents';

    /**
     * @var string
     */
    protected $_cacheTag = 'rptech_manageEvents';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'rptech_manageEvents';

    /**
     * Initialize resource model.
     */
    protected function _construct() {
        $this->_init('Manageevents\Events\Model\ResourceModel\Grid');
    }

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId() {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId) {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getTitle() {
        return $this->getData(self::TITLE);
    }

    /**
     * Set Title.
     */
    public function setTitle($title) {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Get getContent.
     *
     * @return varchar
     */
    public function getContent() {
        return $this->getData(self::CONTENT);
    }

    /**
     * Set Content.
     */
    public function setContent($content) {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Get getContent.
     *
     * @return varchar
     */

    public function getCity() {
        return $this->getData(self::CITY);
    }

    public function setCity($city) {
        return $this->setData(self::CITY, $city);
    }

    public function getEventimage1() {
        return $this->getData(self::EVENT_IMAGE_1);
    }

    public function setEventimage1($eventimage1) {
        return $this->setData(self::EVENT_IMAGE_1, $eventimage1);
    }

    public function getEventimage2() {
        return $this->getData(self::EVENT_IMAGE_2);
    }

    public function setEventimage2($eventimage2) {
        return $this->setData(self::EVENT_IMAGE_2, $eventimage2);
    }

    public function getEventimage3() {
        return $this->getData(self::EVENT_IMAGE_3);
    }

    public function setEventimage3($eventimage3){
        return $this->setData(self::EVENT_IMAGE_3, $eventimage3);
    }

    public function getEventimage4() {
        return $this->getData(self::EVENT_IMAGE_4);
    }

    public function setEventimage4($eventimage4) {
        return $this->setData(self::EVENT_IMAGE_4, $eventimage4);
    }

    public function getEventimage5() {
        return $this->getData(self::EVENT_IMAGE_5);
    }

    public function setEventimage5($eventimage5) {
        return $this->setData(self::EVENT_IMAGE_5, $eventimage5);
    }

    public function getEventimage6() {
        return $this->getData(self::EVENT_IMAGE_6);
    }

    public function setEventimage6($eventimage6) {
        return $this->setData(self::EVENT_IMAGE_6, $eventimage6);
    }

    public function getEventimage7() {
        return $this->getData(self::EVENT_IMAGE_7);
    }

    public function setEventimage7($eventimage7) {
        return $this->setData(self::EVENT_IMAGE_7, $eventimage7);
    }

    public function getEventimage8() {
        return $this->getData(self::EVENT_IMAGE_8);
    }

    public function setEventimage8($eventimage8) {
        return $this->setData(self::EVENT_IMAGE_8, $eventimage8);
    }

    public function getEventimage9() {
        return $this->getData(self::EVENT_IMAGE_9);
    }

    public function setEventimage9($eventimage9) {
        return $this->setData(self::EVENT_IMAGE_9, $eventimage9);
    }

    public function getEventimage10() {
        return $this->getData(self::EVENT_IMAGE_10);
    }

    public function setEventimage10($eventimage10) {
        return $this->setData(self::EVENT_IMAGE_10, $eventimage10);
    }

    /**
     * Get PublishDate.
     *
     * @return varchar
     */
    public function getPublishDate() {
        return $this->getData(self::PUBLISH_DATE);
    }

    /**
     * Set PublishDate.
     */
    public function setPublishDate($publishDate) {
        return $this->setData(self::PUBLISH_DATE, $publishDate);
    }

    /**
     * Get IsActive.
     *
     * @return varchar
     */
    public function getIsActive() {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set IsActive.
     */
    public function setIsActive($isActive) {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getUpdateTime() {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Set UpdateTime.
     */
    public function setUpdateTime($updateTime) {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt() {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set CreatedAt.
     */
    public function setCreatedAt($createdAt) {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

}
