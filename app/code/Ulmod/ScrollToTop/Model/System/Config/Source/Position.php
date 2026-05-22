<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\ScrollToTop\Model\System\Config\Source;

class Position implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'left', 'label' => __('Left')],
            ['value' => 'right', 'label' => __('Right')]
        ];
    }
}
