<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier;

use Magento\Framework\View\Element\Template\Context;

class Dashboard extends \Magento\Framework\View\Element\Template
{
    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->session=$context->getSession();
        parent::__construct($context);
    }

    /**
     * Supplier data
     * @return Object
     */
    protected function _getSupplierData()
    {
        return $this->session->getSupplier();
    }

    /**
     * Supplier address data
     * @return Object
     */
    protected function _getSupplierAddressData()
    {
        return $this->session->getSupplierAddress();
    }
}
