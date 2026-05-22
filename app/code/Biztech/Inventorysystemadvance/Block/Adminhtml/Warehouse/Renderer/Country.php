<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer;

class Country extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $_countryModel;

    /**
     * @param \Magento\Directory\Model\Country $countryModel [description]
     */
    public function __construct(\Magento\Directory\Model\Country $countryModel)
    {
        $this->_countryModel = $countryModel;
    }

    /**
     * This function is used for get the country for the warehouse
     * @param  \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'country') {
            $this->countryHelper = $this->_countryModel;
            $country = $this->countryHelper->loadByCode($row->getCountry());
            $txtbox .= "<span>" . $country->getName() . "</span>";
        }
        return $txtbox;
    }
}
