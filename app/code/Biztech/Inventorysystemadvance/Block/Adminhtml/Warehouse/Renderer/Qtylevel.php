<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer;

class Qtylevel extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * This function is used for check qty level
     * @param  \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex()=='qty_level') {
            $txtbox .= "<select id='product_qty[".$row->getId()."][qty_level]' name='product_qty[".$row->getId()."][qty_level]'>
                            <option value='1'>Increase Qty.</option>
                            <option value='2'>Decrease Qty.</option>
                        </select>";
        }
        return $txtbox;
    }
}
