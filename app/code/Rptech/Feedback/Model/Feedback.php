<?php

namespace Rptech\Feedback\Model;

use Magento\Framework\Model\AbstractModel;
use Rptech\Feedback\Api\Data\FeedbackInterface;
use Rptech\Feedback\Model\ResourceModel\Feedback as FeedbackResourceModel;

class Feedback extends AbstractModel implements FeedbackInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = FeedbackInterface::TABLE_NAME;

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(FeedbackResourceModel::class);
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
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->getData(FeedbackInterface::KEY_ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(FeedbackInterface::KEY_ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->getData(FeedbackInterface::KEY_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setType($type)
    {
        return $this->setData(FeedbackInterface::KEY_TYPE, $type);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(FeedbackInterface::KEY_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(FeedbackInterface::KEY_NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->getData(FeedbackInterface::KEY_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        return $this->setData(FeedbackInterface::KEY_DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getEmail()
    {
        return $this->getData(FeedbackInterface::KEY_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setEmail($email)
    {
        return $this->setData(FeedbackInterface::KEY_EMAIL, $email);
    }

    /**
     * @inheritDoc
     */
    public function getPhone()
    {
        return $this->getData(FeedbackInterface::KEY_PHONE);
    }

    /**
     * @inheritDoc
     */
    public function setPhone($phone)
    {
        return $this->setData(FeedbackInterface::KEY_PHONE, $phone);
    }

    /**
     * @inheritDoc
     */
    public function getAddress()
    {
        return $this->getData(FeedbackInterface::KEY_ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setAddress($address)
    {
        return $this->setData(FeedbackInterface::KEY_ADDRESS, $address);
    }

    /**
     * @inheritDoc
     */
    public function getState()
    {
        return $this->getData(FeedbackInterface::KEY_STATE);
    }

    /**
     * @inheritDoc
     */
    public function setState(string $state)
    {
        return $this->setData(FeedbackInterface::KEY_STATE, $state);
    }

    /**
     * @inheritDoc
     */
    public function getMobile()
    {
        return $this->getData(FeedbackInterface::KEY_MOBILE);
    }

    /**
     * @inheritDoc
     */
    public function setMobile(string $mobile)
    {
        return $this->setData(FeedbackInterface::KEY_MOBILE, $mobile);
    }

    /**
     * @inheritDoc
     */
    public function getPincode()
    {
        return $this->getData(FeedbackInterface::KEY_PINCODE);
    }

    /**
     * @inheritDoc
     */
    public function setPincode(string $pincode)
    {
        return $this->setData(FeedbackInterface::KEY_PINCODE, $pincode);
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return $this->getData(FeedbackInterface::KEY_CITY);
    }

    /**
     * @inheritDoc
     */
    public function setCity(string $city)
    {
        return $this->setData(FeedbackInterface::KEY_CITY, $city);
    }
    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(FeedbackInterface::KEY_CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(FeedbackInterface::KEY_CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(FeedbackInterface::KEY_UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(FeedbackInterface::KEY_UPDATED_AT, $updatedAt);
    }
}
