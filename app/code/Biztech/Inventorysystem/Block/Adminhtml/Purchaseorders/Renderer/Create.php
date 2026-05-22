<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Create extends AbstractRenderer
{
    protected $_pricingHelper;
    protected $_currencyInterface;
    protected $_currentStore;

    /**
     * @param \Magento\Framework\Pricing\Helper\Data      $pricingHelper
     * @param \Magento\Framework\Locale\CurrencyInterface $currencyInterface
     * @param \Magento\Store\Model\Store                  $currentStore
     */
    public function __construct(
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        \Magento\Store\Model\Store $currentStore
    ) {
        $this->_pricingHelper = $pricingHelper;
        $this->_currencyInterface = $currencyInterface;
        $this->_currentStore = $currentStore;
    }

    /**
     * @param  DataObject $row
     * @return String
     */
    public function render(DataObject $row)
    {
        
        $priceHelper = $this->_pricingHelper;
        $_localeCurrency = $this->_currencyInterface;
        $txtbox = '';
        if ($this->getColumn()->getIndex()=='cost') {
            $curCode = $this->_currentStore->getCurrentCurrencyCode();
            $curSym = $_localeCurrency->getCurrency($curCode)->getSymbol();
            if ($row->getCost()) {
                $txtbox .= "<span>".$curSym.number_format($row->getCost(), 2)."</span><input type='hidden' class='cost' id='cost_".$row->getEntityId()."' name='cost[".$row->getEntityId()."]' value='".number_format($row->getCost(), 2)."' />&nbsp;&nbsp;&nbsp;<input type='text' class='inpCost' size='5' id='input_cost_".$row->getEntityId()."' name='input_cost[".$row->getEntityId()."]' item_id='".$row->getEntityId()."' data-validate='{'validate-greater-than-zero': true, 'validate-not-negative-number': true, 'validate-number': true}'/>";
            } else {
                $txtbox .= "<span>".$curSym."0.00</span><input type='hidden' class='cost' id='cost_".$row->getEntityId()."' name='cost[".$row->getEntityId()."]' value='0' />&nbsp;&nbsp;&nbsp;<input type='text' class='inpCost' size='5' id='input_cost_".$row->getEntityId()."' name='input_cost[".$row->getEntityId()."]' item_id='".$row->getEntityId()."' data-validate='{'validate-greater-than-zero': true, 'validate-not-negative-number': true, 'validate-number': true}'/>";
            }
        } else if ($this->getColumn()->getIndex()=='row_total') {
            $txtbox .= "<input type='text' class='admin__control-text rowTotal' disabled='disabled' id='row_total_".$row->getEntityId()."' size='5' name='row_total[".$row->getEntityId()."]' />";
        } else if ($this->getColumn()->getIndex() == 'sku') {
            $txtbox .= "<input type='hidden' id='sku' name='sku[".$row->getEntityId()."]' value='".$row->getSku()."' />";
            $txtbox .= "<input type='hidden' id='name' name='name[".$row->getEntityId()."]' value='".$row->getName()."' />";
            $txtbox .= "<input type='hidden' id='qty' name='qty[".$row->getEntityId()."]' value='".round($row->getQty(), 2)."' />";
            $txtbox .= "<span>".$row->getSku()."</span>";
        }
        return $txtbox;
    }
}
