<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Model;

use Magento\Framework\Event\ObserverInterface;

class Observer implements ObserverInterface
{
    /**
     * @param  \Magento\Framework\Event\Observer $observer [description]
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $_product = $observer->getProduct();
    }
}
