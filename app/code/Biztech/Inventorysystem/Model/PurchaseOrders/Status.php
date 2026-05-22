<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\PurchaseOrders;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    /**
     * PO status
     * @return Array
     */
    public function toOptionArray()
    {
        return [
            'pending' => __('Pending'),
            'processing' => __('Processing'),
            'completed' => __('Completed'),
            'canceled' => __('Canceled')
        ];
    }
}
