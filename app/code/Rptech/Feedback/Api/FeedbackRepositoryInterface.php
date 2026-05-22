<?php

namespace Rptech\Feedback\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Rptech\Feedback\Api\Data\FeedbackInterface;
use Rptech\Feedback\Model\Feedback;

/**
 * Interface FeedbackRepositoryInterface
 * @package Rptech\Feedback\Api
 */
interface FeedbackRepositoryInterface
{

    /**
     * Save branch address data
     *
     * @param FeedbackInterface $data
     * @return Feedback
     */
    public function save(FeedbackInterface $data);


    /**
     * Get branch address data by id
     *
     * @param int $id
     * @return Feedback
     */
    public function getById($id);

    /**
     * Get list of branch address data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return FeedbackInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete branch address record
     *
     * @param FeedbackInterface $data
     * @return mixed
     */
    public function delete(FeedbackInterface $data);

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
