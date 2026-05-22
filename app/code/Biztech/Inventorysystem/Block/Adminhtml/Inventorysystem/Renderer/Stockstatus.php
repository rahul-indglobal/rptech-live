<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer;

class Stockstatus extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Manage stock stock status
     * @param  \Magento\Framework\DataObject $row
     * @return String
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'is_in_stock') {
            if ($row->getIsInStock() == 0) {
                $txtbox .= "<div style='background:red' width='80%' align='center'><strong>Out of Stock<strong></div>";
            } else {
                $txtbox .= "<div style='background:lime' width='80%' align='center'><strong>In Stock<strong></div>";
            }
        }
        return $txtbox;
    }
}
