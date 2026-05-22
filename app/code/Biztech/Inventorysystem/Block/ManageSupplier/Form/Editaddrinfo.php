<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier\Form;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Session\SessionManager;

class Editaddrinfo extends \Biztech\Inventorysystem\Block\ManageSupplier\Dashboard
{
    protected $session;
    protected $_regionModel;
    protected $_regionCollection;

    /**
     * @param Context                                                          $context
     * @param SessionManager                                                   $session
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Directory\Model\Region                                  $regionModel
     * @param \Magento\Directory\Model\ResourceModel\Region\Collection         $regionCollection
     */
    public function __construct(
        Context $context,
        SessionManager $session,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Directory\Model\Region $regionModel,
        \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection
    ) {
        $this->session = $session;
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->_regionModel = $regionModel;
        $this->_regionCollection = $regionCollection;
        parent::__construct($context);
    }

    /**
     * Supplier street address
     * @return String
     */
    public function getStreetAddress()
    {
        return $this->_getSupplierAddressData()->getAddressLine();
    }

    /**
     * Supplier city
     * @return String
     */
    public function getCity()
    {
        return $this->_getSupplierAddressData()->getCity();
    }

    /**
     * Supplier country
     * @return mixed
     */
    public function getCountry()
    {
        $countryData = $this->_countryCollectionFactory->create()->loadByStore()->toOptionArray(true);
        $countryData[0]['label'] = "--Please Select Country--";
        $selectHtml = "<select id='country' name='country' onchange='getstate(this)'>";
        foreach ($countryData as $key => $data) {
            if ($data['value'] == $this->_getSupplierAddressData()->getCountry()) {
                $selectHtml .= "<option value='".$data['value']."' selected='selected'>".$data['label']."</option>";
            } else {
                $selectHtml .= "<option value='".$data['value']."'>".$data['label']."</option>";
            }
        }
        return $selectHtml .= "</select>";
    }
    
    /**
     * Supplier state
     * @return mixed
     */
    public function getState()
    {

        $getState   =  $this->_getSupplierAddressData()->getState();
        $region = $this->_regionModel->load($getState);
        if (!empty($region->getData())) {
            $getStateID = $region->getData('region_id');
            $countryCode = $region->getData('country_id');
            $stateCode = $region->getData('code');
        } else {
            $getStateID = "";
        }
        $stateHtml = '';
        if (!$getStateID) {
            return $stateHtml = "<input class='input-text' type='text' id='state' name='state' value='".$getState."'><input type='hidden' value='' name='state_id' id='state_id' />";
        } else {
            $stateCollection = $this->_regionCollection;
            $stateCollection->addFieldToFilter('country_id', $countryCode);
            $state   = "";
            $stateHtml = "<select id='state_id' name='state_id'>";
            foreach ($stateCollection->getData() as $_state) {
                if ($_state['code'] == $stateCode) {
                    $stateHtml .= "<option value='".$_state['code']."' selected='selected'>".$_state['default_name']."</option>";
                } else {
                    $stateHtml .= "<option value='".$_state['code']."'>".$_state['default_name']."</option>";
                }
            }
            return $stateHtml .= "</select><input type='hidden' value='' name='state' id='state' />";
        }
    }
    
    /**
     * Supplier postalcode
     * @return int
     */
    public function getPostalCode()
    {
        return $this->_getSupplierAddressData()->getPostalCode();
    }
    
    /**
     * Supplier telephoen
     * @return int
     */
    public function getTelephone()
    {
        return $this->_getSupplierAddressData()->getTelephone();
    }
}
