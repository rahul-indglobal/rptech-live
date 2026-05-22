<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Stockreceived\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Stockreceivedviewlink extends AbstractRenderer
{
    /**
     * Stockreceived view link
     * @param  DataObject $row
     * @return String
     */
    public function render(DataObject $row)
    {
        $txtbox = '';

        if ($this->getColumn()->getIndex() == 'stockreceived_id') {
            $incrID = $row->getStockreceivedId();
            $txtbox .= "<span><a href='".$this->getUrl('inventorysystem/stockreceived/view', ['sr_incr_id'=>$incrID])."'>".$incrID."</a></span>";
        }

        return $txtbox;
    }
}
