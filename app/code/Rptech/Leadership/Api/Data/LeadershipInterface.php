<?php

namespace Rptech\Leadership\Api\Data;

use Rptech\Leadership\Model\Leadership;

/**
 * Interface LeadershipInterface
 * @package Rptech\Leadership\Api\Data
 */
interface LeadershipInterface
{
    const TABLE_NAME = "leadership";
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const KEY_ENTITY_ID = 'entity_id';
    const KEY_TITLE = 'title';
    const KEY_DESCRIPTION = 'description';
    const KEY_IMAGE = 'image';
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
     * @return LeadershipInterface
     */
    public function setEntityId($entityId);

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set Title
     *
     * @param string $title
     * @return LeadershipInterface
     */
    public function setTitle($title);

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
     * @return LeadershipInterface
     */
    public function setDescription($description);

    /**
     * Get Image
     *
     * @return string
     */
    public function getImage();

    /**
     * Set Image
     *
     * @param string $image
     * @return LeadershipInterface
     */
    public function setImage($image);

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
     * @return LeadershipInterface
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
     * @return LeadershipInterface
     */
    public function setUpdatedAt($updatedAt);
}
