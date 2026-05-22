<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer;

class Comment extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Manage stock comment
     * @param  \Magento\Framework\DataObject $row
     * @return String
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'comment') {
            $txtbox .= "<span><textarea id='product[" . $row->getId() . "][comment]' name='product[" . $row->getId() . "][comment]' cols=1 rows=3 style='width:95%;height:95%'></textarea></span>";
        }
        return $txtbox;
    }
}
