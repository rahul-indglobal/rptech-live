<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model\Managehistory;

use Magento\Framework\Option\ArrayInterface;

class Actiontype implements ArrayInterface
{
    /**
     * Managehistory action type
     * @return Array
     */
    public function toOptionArray()
    {
        $options = [
            'Quantity Increased' => __('Quantity Increased'),
            'Quantity Decreased' => __('Quantity Decreased')
        ];

        return $options;
    }
}
