<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer;

class Totalqty extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * This function is used for get total qty
     * @param  \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'total_qty') {
            $txtbox .= "<span><input class='input-text no-changes' type='text' id='product_qty[" . $row->getId() . "][qty]' name='product_qty[" . $row->getId() . "][qty]' value='0' style=\"width:80%\"></span>";
        }
        return $txtbox;
    }
}
