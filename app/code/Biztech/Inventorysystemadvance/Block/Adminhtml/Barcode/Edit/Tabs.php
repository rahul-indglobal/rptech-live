<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('checkmodule_barcode_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Barcode Information'));
    }
}
