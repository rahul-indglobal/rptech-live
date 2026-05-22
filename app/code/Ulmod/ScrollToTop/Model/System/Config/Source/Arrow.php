<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\ScrollToTop\Model\System\Config\Source;

class Arrow implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'fa-arrow-circle-up', 'label' => __('Arrow Circle Up 1')],
            ['value' => 'fa-arrow-up', 'label' => __('Arrow Up')],
            ['value' => 'fa-arrow-circle-o-up', 'label' => __('Arrow Circle Up 2')],
            ['value' => 'fa-chevron-up', 'label' => __('Chevron Up')],
            ['value' => 'fa-long-arrow-up', 'label' => __('Long Arrow Up')],
            ['value' => 'fa-chevron-circle-up', 'label' => __('Chevron Circle Up')],
            ['value' => 'fa-caret-up', 'label' => __('Caret Up')],
            ['value' => 'fa-angle-double-up', 'label' => __('Angle Double Up')],
            ['value' => 'fa-caret-square-o-up', 'label' => __('Caret Square Up')],
            ['value' => 'fa-level-up', 'label' => __('Level Up')],
            ['value' => 'fa-angle-up', 'label' => __('Angle Up')],
        ];
    }
}
