<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier\Form;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Session\SessionManager;

class Editinfo extends \Biztech\Inventorysystem\Block\ManageSupplier\Dashboard
{
    protected $_session;

    /**
     * @param Context        $context
     * @param SessionManager $session
     */
    public function __construct(
        Context $context,
        SessionManager $session
    ) {
        $this->_session = $session;
        parent::__construct($context);
    }

    /**
     * Supplier firstname
     * @return String
     */
    public function getSupplierFirstName()
    {
        return $this->_getSupplierData()->getFirstName();
    }

    /**Supplier lastname
     * @return String
     */
    public function getSupplierLastName()
    {
        return $this->_getSupplierData()->getLastName();
    }
    
    /**
     * Supplier company
     * @return String
     */
    public function getSupplierCompany()
    {
        return $this->_getSupplierData()->getCompany();
    }
}
