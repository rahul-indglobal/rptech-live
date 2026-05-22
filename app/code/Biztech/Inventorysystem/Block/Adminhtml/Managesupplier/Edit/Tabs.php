<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Managesupplier\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return Void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('inventorysystem_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Supplier Information'));
    }
}
