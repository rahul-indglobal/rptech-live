<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\Purchaseinvoice;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    
    /**
     * Options Array
     * @return Array
     */
    public function toOptionArray()
    {
        return [
            'pending' => __('Pending'),
            'paid' => __('Paid'),
            'cancel' => __('Cancel')
        ];
    }
}
