<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model\Managehistory;

use Magento\Framework\Option\ArrayInterface;

class Systemaction implements ArrayInterface
{
    /**
     * Managehistory statuses
     * @return Array
     */
    public function toOptionArray()
    {
        $options = [
            'Goods Received' => __('Goods Received'),
            'Inventory Update' => __('Inventory Update'),
            'Manage Stock' => __('Manage Stock'),
            'Order Created' => __('Order Created'),
            'Order Canceled' => __('Order Canceled'),
            'Credit Memo' => __('Credit Memo'),
            'Warehouse Transaction' => __('Warehouse Transaction'),
        ];

        return $options;
    }
}
