<?php

namespace Rptech\BranchAddress\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Rptech\BranchAddress\Api\Data\BranchAddressInterface;
use Rptech\BranchAddress\Model\BranchAddress;

/**
 * Interface BranchAddressRepositoryInterface
 * @package Rptech\BranchAddress\Api
 */
interface BranchAddressRepositoryInterface
{

    /**
     * Save branch address data
     *
     * @param BranchAddressInterface $data
     * @return BranchAddress
     */
    public function save(BranchAddressInterface $data);


    /**
     * Get branch address data by id
     *
     * @param int $id
     * @return BranchAddress
     */
    public function getById($id);

    /**
     * Get list of branch address data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return BranchAddressInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete branch address record
     *
     * @param BranchAddressInterface $data
     * @return mixed
     */
    public function delete(BranchAddressInterface $data);

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
