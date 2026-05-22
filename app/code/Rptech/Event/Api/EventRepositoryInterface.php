<?php

namespace Rptech\Event\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Rptech\Event\Api\Data\EventInterface;
use Rptech\Event\Model\Event;

/**
 * Interface EventRepositoryInterface
 * @package Rptech\Event\Api
 */
interface EventRepositoryInterface
{
    /**
     * Save Event address data
     *
     * @param EventInterface $data
     * @return Event
     */
    public function save(EventInterface $data);


    /**
     * Get branch address data by id
     *
     * @param int $id
     * @return Event
     */
    public function getById($id);

    /**
     * Get list of Event data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return EventInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Event record
     *
     * @param EventInterface $data
     * @return mixed
     */
    public function delete(EventInterface $data);

    /**
     * Delete Event record id
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws StateException
     * @throws CouldNotSaveException
     */
    public function deleteById($id);
}