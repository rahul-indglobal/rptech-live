<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\Config\Source;

class Enabledisable implements \Magento\Framework\Option\ArrayInterface
{

    protected $helper;
    /**
     * @param \Biztech\Inventorysystem\Helper\Data $helperData
     */
    public function __construct(
        \Biztech\Inventorysystem\Helper\Data $helperData
    ) {
        $this->helper = $helperData;
    }

    /**
     * This function used for the get the Yes and No options for the configuration
     * @return Array Yes No options
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => 0, 'label' => __('No')],
        ];
        $websites = $this->helper->getAllWebsites();
        if (!empty($websites)) {
            $options[] = ['value' => 1, 'label' => __('Yes')];
        }
        return $options;
    }
}
