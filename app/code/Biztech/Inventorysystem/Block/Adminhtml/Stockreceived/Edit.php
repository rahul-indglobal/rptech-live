<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Stockreceived;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{

    /**
     * @return Void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_controller = 'adminhtml_stockreceived';

        parent::_construct();

        // $this->buttonList->update('save', 'label', __('Create PO'));
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
        $this->buttonList->remove('save');

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('inventorysystem_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'inventorysystem_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'inventorysystem_content');
                }
            }
        ";
    }
}
