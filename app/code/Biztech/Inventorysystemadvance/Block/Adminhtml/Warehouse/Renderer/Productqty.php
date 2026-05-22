<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer;

class Productqty extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * This function is used for get product qty
     * @param  \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'quantity') {
            if ($row->getQuantity() == null) {
                $qty = 0;
            } else {
                $qty = $row->getQuantity();
            }
            $txtbox .= "<span>".(int)$qty."</span>";
        }
        if ($this->getColumn()->getIndex() == 'qty') {
            $txtbox .= "<span>".(int)$row->getQty()."<input type='hidden' id='product_total_qty[" . $row->getId() . "][total_qty]' value='" . (int)$row->getQty() . "' name='product_total_qty[" . $row->getId() . "][total_qty]'></span>";
        }
           
        return $txtbox;
    }
}
