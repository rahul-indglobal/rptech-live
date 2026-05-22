<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer;

class Totalqty extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Manage stock total qty
     * @param  \Magento\Framework\DataObject $row
     * @return String
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'total_qty') {
            $txtbox .= "<span><input type='text' id='product[" . $row->getId() . "][total_qty]' name='product[" . $row->getId() . "][total_qty]' style=\"width:100%\"></span>";
        }
        return $txtbox;
    }
}
