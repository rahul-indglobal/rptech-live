<?php

namespace Rptech\CareerOpportunities\Api\Data;

/**
 * Interface CareerOpportunitiesInterface
 * @package Rptech\CareerOpportunities\Api\Data
 */
interface CareerOpportunitiesInterface
{
    const TABLE_NAME = "career_opportunities";
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const KEY_ENTITY_ID = 'entity_id';
    const KEY_NAME = 'name';
    const KEY_POST = 'post';
    const KEY_CITY = 'city';
    const KEY_AGE = 'age';
    const KEY_QUALIFICATION = 'qualification';
    const KEY_EXPERIENCE = 'experience';
    const KEY_EMAIL = 'email';
    const KEY_MOBILE = 'mobile';
    const KEY_REMARK = 'remark';
    const KEY_CV_FILE = 'cv_file';
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
     * @return CareerOpportunitiesInterface
     */
    public function setEntityId($entityId);

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
     * @return CareerOpportunitiesInterface
     */
    public function setName($name);

    /**
     * Get Post
     *
     * @return string
     */
    public function getPost();

    /**
     * Set Name
     *
     * @param string $post
     * @return CareerOpportunitiesInterface
     */
    public function setPost($post);

    /**
     * Get Age
     *
     * @return string
     */
    public function getAge();

    /**
     * Set Age
     *
     * @param string $age
     * @return CareerOpportunitiesInterface
     */
    public function setAge($age);

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
     * @return CareerOpportunitiesInterface
     */
    public function setEmail($email);

    /**
     * Get Remark
     *
     * @return string
     */
    public function getRemark();

    /**
     * Set Remark
     *
     * @param string $remark
     * @return CareerOpportunitiesInterface
     */
    public function setRemark($remark);

    /**
     * Get Qualification
     *
     * @return string
     */
    public function getQualification();

    /**
     * Set Qualification
     *
     * @param string $qualification
     * @return CareerOpportunitiesInterface
     */
    public function setQualification($qualification);

    /**
     * Get Experience
     *
     * @return string
     */
    public function getExperience();

    /**
     * Set Experience
     *
     * @param string $experience
     * @return CareerOpportunitiesInterface
     */
    public function setExperience(string $experience);

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
     * @return CareerOpportunitiesInterface
     */
    public function setMobile(string $mobile);

    /**
     * Get CvFile
     *
     * @return string
     */
    public function getCvFile();

    /**
     * Set CvFile
     *
     * @param string $cvfile
     * @return CareerOpportunitiesInterface
     */
    public function setCvFile(string $cvfile);

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
     * @return CareerOpportunitiesInterface
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
     * @return CareerOpportunitiesInterface
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
     * @return CareerOpportunitiesInterface
     */
    public function setUpdatedAt($updatedAt);
}
