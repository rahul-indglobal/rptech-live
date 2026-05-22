<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model\ResourceModel\Warehouse;

use Magento\Framework\Option\ArrayInterface;

class Primary implements ArrayInterface
{
    /**
     * Warehouse options
     * @return Array
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => 1, 'label' => __('Yes')],
            ['value' => 0, 'label' => __('No')]
        ];

        return $options;
    }
}
