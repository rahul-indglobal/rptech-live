<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model\Barcode;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    /**
     * @return Array
     */
    public function toOptionArray()
    {
        $options = [
            '1' => __('Enable'),
            '2' => __('Disable')
        ];

        return $options;
    }
}
