<?php

namespace Rptech\CareerOpportunities\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface CareerOpportunitiesSearchResultsInterface
 * @package Rptech\CareerOpportunities\Api\Data
 */
interface CareerOpportunitiesSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return CareerOpportunitiesInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param CareerOpportunitiesInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
