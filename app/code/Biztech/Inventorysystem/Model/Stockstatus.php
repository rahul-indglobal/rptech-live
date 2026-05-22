<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model;

class Stockstatus extends \Magento\Framework\DataObject
{
    const IN_STOCK  = 1;
    const OUT_STOCK = 2;

    /**
     * @param array $data
     */
    public function __construct(
        array $data = []
    ) {
        parent::__construct(
            $data
        );
    }

    /**
     * Stock status
     * @return Array
     */
    public static function getOptionArray()
    {
        return array(
            self::IN_STOCK    => __('In Stock'),
            self::OUT_STOCK   => __('Out of Stock')
        );
    }
}
