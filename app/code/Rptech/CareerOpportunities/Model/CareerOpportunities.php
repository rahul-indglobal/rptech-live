<?php

namespace Rptech\CareerOpportunities\Model;

use Magento\Framework\Model\AbstractModel;
use Rptech\CareerOpportunities\Api\Data\CareerOpportunitiesInterface;
use Rptech\CareerOpportunities\Model\ResourceModel\CareerOpportunities as CareerOpportunitiesResourceModel;

class CareerOpportunities extends AbstractModel implements CareerOpportunitiesInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = CareerOpportunitiesInterface::TABLE_NAME;

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(CareerOpportunitiesResourceModel::class);
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
        return $this->getData(CareerOpportunitiesInterface::KEY_ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_ENTITY_ID, $entityId);
    }


    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_UPDATED_AT, $updatedAt);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_NAME);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function setName($name)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_NAME, $name);
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_POST);
    }

    /**
     * @param string $post
     * @return mixed
     */
    public function setPost($post)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_POST, $post);
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_AGE);
    }

    /**
     * @param string $age
     * @return mixed
     */
    public function setAge($age)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_AGE, $age);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_EMAIL);
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function setEmail($email)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_EMAIL, $email);
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_REMARK);
    }

    /**
     * @param string $remark
     * @return mixed
     */
    public function setRemark($remark)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_REMARK, $remark);
    }

    /**
     * @return mixed
     */
    public function getQualification()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_QUALIFICATION);
    }

    /**
     * @param string $qualification
     * @return mixed
     */
    public function setQualification($qualification)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_QUALIFICATION, $qualification);
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_EXPERIENCE);
    }

    /**
     * @param string $experience
     * @return mixed
     */
    public function setExperience(string $experience)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_EXPERIENCE, $experience);
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_MOBILE);
    }

    /**
     * @param string $mobile
     * @return mixed
     */
    public function setMobile(string $mobile)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_MOBILE, $mobile);
    }

    /**
     * @return mixed
     */
    public function getCvFile()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_CV_FILE);
    }

    /**
     * @param string $cvfile
     * @return mixed
     */
    public function setCvFile(string $cvfile)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_CV_FILE, $cvfile);
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->getData(CareerOpportunitiesInterface::KEY_CITY);
    }

    /**
     * @param string $city
     * @return mixed
     */
    public function setCity(string $city)
    {
        return $this->setData(CareerOpportunitiesInterface::KEY_CITY, $city);
    }
}
