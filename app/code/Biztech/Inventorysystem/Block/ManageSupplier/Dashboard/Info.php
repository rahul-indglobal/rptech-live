<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier\Dashboard;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Session\SessionManager;
use Biztech\Inventorysystem\Model\Managesupplieraddr;

class Info extends \Biztech\Inventorysystem\Block\ManageSupplier\Dashboard
{
    protected $_session;
    protected $_managesupplieraddr;
    protected $_regionModel;
    protected $_countryModel;

    /**
     * @param Context                          $context
     * @param SessionManager                   $session
     * @param Managesupplieraddr               $managesupplieraddr
     * @param \Magento\Directory\Model\Region  $regionModel
     * @param \Magento\Directory\Model\Country $countryModel
     */
    public function __construct(
        Context $context,
        SessionManager $session,
        Managesupplieraddr $managesupplieraddr,
        \Magento\Directory\Model\Region $regionModel,
        \Magento\Directory\Model\Country $countryModel
    ) {
        $this->_session = $session;
        $this->_managesupplieraddr = $managesupplieraddr;
        $this->_regionModel = $regionModel;
        $this->_countryModel = $countryModel;
        parent::__construct($context);
    }

    /**
     * Supplier data
     * @return Object
     */
    protected function _getSupplierData()
    {
        return $this->_session->getSupplier();
    }

    /**
     * Supplier address data
     * @return Object
     */
    protected function getSupplierAddressData()
    {
        return $this->_getSupplierData();
    }

    /**
     * Supplier name
     * @return String
     */
    public function getSupplierName()
    {
        return $this->_getSupplierData()->getFirstName() . ' ' . $this->_getSupplierData()->getLastName();
    }

    /**
     * Supplier email
     * @return String
     */
    public function getSupplierEmail()
    {
        return $this->_getSupplierData()->getEmail();
    }

    /**
     * Supplier company
     * @return String
     */
    public function getSupplierCompany()
    {
        return $this->_getSupplierData()->getCompany();
    }

    /**
     * Supplier address data
     * @return Object
     */
    public function getSupplierAddressData1()
    {
        return $this->_managesupplieraddr->load($this->_getSupplierData()->getSupplierId(), 'supplier_id');
    }

    /**
     * Region details
     * @return Object
     */
    public function getRegion()
    {
        return $this->_regionModel;
    }

    /**
     * Country details
     * @return Object
     */
    public function getCountry()
    {
        return $this->_countryModel;
    }
}
