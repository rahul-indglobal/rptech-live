<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Action extends AbstractRenderer
{
    /**
     * PO Status
     * @param  DataObject $row
     * @return String
     */
    public function render(DataObject $row)
    {
        $link = '';
        if ($this->getColumn()->getIndex()=='action') {
            $view     = $this->getUrl('*/*/view', array('id'=>$row->getId()));
            if ($row->getStatus() == 'completed' || $row->getStatus() == 'canceled') {
                $link = '<div class="admin__field"><center><a title="'. __('View') .'" href="'.$view.'">'.__('View').'</a></center></div>';
            } else {
                $createSR = $this->getUrl('*/*/stockreceived', ['porder_id'=>$row->getId()]);
                $link = '<div class="admin__field"><center><a title="'.__('View').'" href="'.$view.'">'.__('View').'</a></center></div><div class="admin__field"><center><a title="'.__('Create SR').'" href="'.$createSR.'">'.__('Create SR').'</a></center></div>';
            }
        }
        return $link;
    }
}
