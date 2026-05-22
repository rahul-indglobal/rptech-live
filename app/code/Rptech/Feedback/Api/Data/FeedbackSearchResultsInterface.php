<?php

namespace Rptech\Feedback\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface FeedbackSearchResultsInterface
 * @package Rptech\Feedback\Api\Data
 */
interface FeedbackSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return FeedbackInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param FeedbackInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
