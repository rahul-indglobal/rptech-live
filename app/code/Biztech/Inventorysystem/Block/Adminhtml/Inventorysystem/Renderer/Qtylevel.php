<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer;

class Qtylevel extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Manage stock options
     * @param  \Magento\Framework\DataObject $row
     * @return Array
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'qty_level') {
            $txtbox .= "<select id='product[" . $row->getId() . "][qty_level]' name='product[" . $row->getId() . "][qty_level]'>
                            <option value='1'>Increase Qty.</option>
                            <option value='2'>Decrease Qty.</option>
                        </select>";
        }
        return $txtbox;
    }
}
