<?php

namespace Rptech\Event\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface EventSearchResultsInterface
 * @package Rptech\Event\Api\Data
 */
interface EventSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return EventInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param EventInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
