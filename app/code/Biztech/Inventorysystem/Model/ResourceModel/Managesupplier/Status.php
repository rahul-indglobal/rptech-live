<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\ResourceModel\Managesupplier;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    /**
     * Supplier status
     * @return Array
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => 1, 'label' => __('Active')],
            ['value' => 0, 'label' => __('Inactive')]
        ];

        return $options;
    }
}
