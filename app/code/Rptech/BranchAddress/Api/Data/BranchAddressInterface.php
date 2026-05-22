<?php

namespace Rptech\BranchAddress\Api\Data;

/**
 * Interface BranchAddressInterface
 * @package Rptech\BranchAddress\Api\Data
 */
interface BranchAddressInterface
{
    const TABLE_NAME = "branch_address";
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const KEY_ENTITY_ID = 'entity_id';
    const KEY_BRANCH_LOCATION = 'branch_location';
    const KEY_BRANCH_ADDRESS = 'branch_address';
    const KEY_BRANCH_PHONE = 'branch_phone';
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
     * @return BranchAddressInterface
     */
    public function setEntityId($entityId);

    /**
     * Get Branch Location
     *
     * @return string
     */
    public function getBranchLocation();

    /**
     * Set Branch Location
     *
     * @param string $branchLocation
     * @return BranchAddressInterface
     */
    public function setBranchLocation($branchLocation);

    /**
     * Get Branch Address
     *
     * @return string
     */
    public function getBranchAddress();

    /**
     * Set Branch Address
     *
     * @param string $branchAddress
     * @return BranchAddressInterface
     */
    public function setBranchAddress($branchAddress);

    /**
     * Get Branch Phone
     *
     * @return string
     */
    public function getBranchPhone();

    /**
     * Set Branch Phone
     *
     * @param string $branchPhone
     * @return BranchAddressInterface
     */
    public function setBranchPhone($branchPhone);

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
     * @return BranchAddressInterface
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
     * @return BranchAddressInterface
     */
    public function setUpdatedAt($updatedAt);
}
