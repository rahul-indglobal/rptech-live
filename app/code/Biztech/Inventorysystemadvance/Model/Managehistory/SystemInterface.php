<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model\Managehistory;

use Magento\Framework\Option\ArrayInterface;

class SystemInterface implements ArrayInterface
{
    /**
     * Managehistory interface
     * @return Array
     */
    public function toOptionArray()
    {
        $options = [
            'Admin' => __('Admin'),
            'Frontend' => __('Frontend'),
            'Mobile' => __('Mobile')
        ];

        return $options;
    }
}
