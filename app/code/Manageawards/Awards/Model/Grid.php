<?php

/**
 * Grid Grid Model.
 * @category  Leaders 
 * @package   Manageleaders_Leaders
 * @author    Lalita Rajput
 */
namespace Manageawards\Awards\Model;

use Manageawards\Awards\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'rptech_awardsContentManage';

    /**
     * @var string
     */
    protected $_cacheTag = 'rptech_awardsContentManage';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'rptech_awardsContentManage';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Manageawards\Awards\Model\ResourceModel\Grid');
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Set Title.
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Get getContent.
     *
     * @return varchar
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Set Content.
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }
    
     /**
     * Get getContent.
     *
     * @return varchar
     */
    public function getAwardImage()
    {
        return $this->getData(self::AWARDIMAGE);
    }
 
    /**
     * Set Content.
     */
    public function setAwardImage($awardphoto)
    {
        return $this->setData(self::AWARDIMAGE, $awardphoto);
    }


    /**
     * Get IsActive.
     *
     * @return varchar
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set IsActive.
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Set UpdateTime.
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set CreatedAt.
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
