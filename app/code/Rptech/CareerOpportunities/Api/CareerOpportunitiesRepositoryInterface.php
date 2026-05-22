<?php

namespace Rptech\CareerOpportunities\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Rptech\CareerOpportunities\Api\Data\CareerOpportunitiesInterface;
use Rptech\CareerOpportunities\Model\CareerOpportunities;

/**
 * Interface CareerOpportunitiesRepositoryInterface
 * @package Rptech\CareerOpportunities\Api
 */
interface CareerOpportunitiesRepositoryInterface
{

    /**
     * Save CareerOpportunities data
     *
     * @param CareerOpportunitiesInterface $data
     * @return CareerOpportunities
     */
    public function save(CareerOpportunitiesInterface $data);


    /**
     * Get branch address data by id
     *
     * @param int $id
     * @return CareerOpportunities
     */
    public function getById($id);

    /**
     * Get list of branch address data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CareerOpportunitiesInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete branch address record
     *
     * @param CareerOpportunitiesInterface $data
     * @return mixed
     */
    public function delete(CareerOpportunitiesInterface $data);

    /**
     * Delete branch address record id
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws StateException
     * @throws CouldNotSaveException
     */
    public function deleteById($id);
}
