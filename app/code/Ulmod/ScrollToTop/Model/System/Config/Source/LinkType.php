<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\ScrollToTop\Model\System\Config\Source;

class LinkType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'arrow', 'label' => __('Arrow')],
            ['value' => 'text', 'label' => __('Text')],
            ['value' => 'image', 'label' => __('Image')]
        ];
    }
}
