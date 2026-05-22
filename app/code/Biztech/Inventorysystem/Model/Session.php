<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model;

class Session extends \Magento\Framework\Session\SessionManager
{
    /**
     * Check session is generated or not
     * @return boolean
     */
    public function isLoggedIn()
    {
        return (bool)$this->getCustomerId()
            && $this->checkCustomerId($this->getId())
            && !$this->getIsCustomerEmulated();
    }

    /**
     * Get customer id
     * @return int
     */
    public function getCustomerId()
    {
        if ($this->storage->getData('customer_id')) {
            return $this->storage->getData('customer_id');
        }
        return null;
    }
}
