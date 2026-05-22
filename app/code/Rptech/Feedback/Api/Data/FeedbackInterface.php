<?php

namespace Rptech\Feedback\Api\Data;

/**
 * Interface FeedbackInterface
 * @package Rptech\Feedback\Api\Data
 */
interface FeedbackInterface
{
    const TABLE_NAME = "feedback";
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const KEY_ENTITY_ID = 'entity_id';
    const KEY_TYPE = 'type';
    const KEY_NAME = 'name';
    const KEY_DESCRIPTION = 'description';
    const KEY_EMAIL = 'email';
    const KEY_PHONE = 'phone';
    const KEY_ADDRESS = 'address';
    const KEY_STATE = 'state';
    const KEY_MOBILE = 'mobile';
    const KEY_PINCODE = 'pincode';
    const KEY_CITY = 'city';
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
     * @return FeedbackInterface
     */
    public function setEntityId($entityId);

    /**
     * Get Type
     *
     * @return string
     */
    public function getType();

    /**
     * Set Type
     *
     * @param string $type
     * @return FeedbackInterface
     */
    public function setType($type);

    /**
     * Get Name
     *
     * @return string
     */
    public function getName();

    /**
     * Set Name
     *
     * @param string $name
     * @return FeedbackInterface
     */
    public function setName($name);

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
     * @return FeedbackInterface
     */
    public function setDescription($description);

    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set Email
     *
     * @param string $email
     * @return FeedbackInterface
     */
    public function setEmail($email);

    /**
     * Get Phone
     *
     * @return string
     */
    public function getPhone();

    /**
     * Set Phone
     *
     * @param string $phone
     * @return FeedbackInterface
     */
    public function setPhone($phone);

    /**
     * Get Address
     *
     * @return string
     */
    public function getAddress();

    /**
     * Set Address
     *
     * @param string $address
     * @return FeedbackInterface
     */
    public function setAddress($address);

    /**
     * Get State
     *
     * @return string
     */
    public function getState();

    /**
     * Set State
     *
     * @param string $state
     * @return FeedbackInterface
     */
    public function setState(string $state);

    /**
     * Get Mobile
     *
     * @return string
     */
    public function getMobile();

    /**
     * Set Mobile
     *
     * @param string $mobile
     * @return FeedbackInterface
     */
    public function setMobile(string $mobile);

    /**
     * Get Pincode
     *
     * @return string
     */
    public function getPincode();

    /**
     * Set Pincode
     *
     * @param string $pincode
     * @return FeedbackInterface
     */
    public function setPincode(string $pincode);

    /**
     * Get City
     *
     * @return string
     */
    public function getCity();

    /**
     * Set City
     *
     * @param string $city
     * @return FeedbackInterface
     */
    public function setCity(string $city);

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
     * @return FeedbackInterface
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
     * @return FeedbackInterface
     */
    public function setUpdatedAt($updatedAt);
}
