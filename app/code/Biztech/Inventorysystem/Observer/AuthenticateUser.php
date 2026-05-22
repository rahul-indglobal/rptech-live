<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Observer;

use Magento\Framework\Event\ObserverInterface;
use Biztech\Inventorysystem\Helper\Data;
use Magento\Framework\Message\ManagerInterface;

class AuthenticateUser implements ObserverInterface
{
    protected $_bizhelper;
    protected $messageManager;

    /**
     * @param Data             $bizHelper
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Data $bizHelper,
        ManagerInterface $messageManager
    ) {
        $this->_bizhelper = $bizHelper;
        $this->messageManager = $messageManager;
    }
    /**
     * This function is used for the authenticate the user
     * @param  \Magento\Framework\Event\Observer $observer
     * @return bool
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_bizhelper->isEnable()) {
            $this->messageManager->addError(__('Extension- Magemob Inventory is not enabled. Please enable it from Store > Configuration > Biztech > Magemob Inventory.'));
            return false;
        }
        return true;
    }
}
