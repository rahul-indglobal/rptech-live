<?php

namespace Rptech\BranchAddress\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface BranchAddressSearchResultsInterface
 * @package Rptech\BranchAddress\Api\Data
 */
interface BranchAddressSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return BranchAddressInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param BranchAddressInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
