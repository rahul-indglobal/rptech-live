<?php

namespace Rptech\BranchAddress\Model;

use Magento\Framework\Model\AbstractModel;
use Rptech\BranchAddress\Api\Data\BranchAddressInterface;
use Rptech\BranchAddress\Model\ResourceModel\BranchAddress as BranchAddressResourceModel;

class BranchAddress extends AbstractModel implements BranchAddressInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = BranchAddressInterface::TABLE_NAME;

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(BranchAddressResourceModel::class);
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
        return $this->getData(BranchAddressInterface::KEY_ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(BranchAddressInterface::KEY_ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getBranchLocation()
    {
        return $this->getData(BranchAddressInterface::KEY_BRANCH_LOCATION);
    }

    /**
     * @inheritDoc
     */
    public function setBranchLocation($branchLocation)
    {
        return $this->setData(BranchAddressInterface::KEY_BRANCH_LOCATION, $branchLocation);
    }

    /**
     * @inheritDoc
     */
    public function getBranchAddress()
    {
        return $this->getData(BranchAddressInterface::KEY_BRANCH_ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setBranchAddress($branchAddress)
    {
        return $this->setData(BranchAddressInterface::KEY_BRANCH_ADDRESS, $branchAddress);
    }

    /**
     * @inheritDoc
     */
    public function getBranchPhone()
    {
        return $this->getData(BranchAddressInterface::KEY_BRANCH_PHONE);
    }

    /**
     * @inheritDoc
     */
    public function setBranchPhone($branchPhone)
    {
        return $this->setData(BranchAddressInterface::KEY_BRANCH_PHONE, $branchPhone);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(BranchAddressInterface::KEY_CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(BranchAddressInterface::KEY_CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(BranchAddressInterface::KEY_UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(BranchAddressInterface::KEY_UPDATED_AT, $updatedAt);
    }
}
