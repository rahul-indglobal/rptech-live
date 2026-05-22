<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('inventorysystem_purchaseorders_data')->getId()) {
            return __("Edit Item '%1'", $this->escapeHtml($this->_coreRegistry->registry('inventorysystem_purchaseorders_data')->getTitle()));
        } else {
            return __('Generate PO');
        }
    }

    /**
     * @return Void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_controller = 'adminhtml_purchaseorders';

        parent::_construct();

        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');

        if ($this->getRequest()->getPostValue('massaction_prepare_key') == 'pendingitems') {
            $this->setTemplate('Biztech_Inventorysystem::purchaseorders/createpo.phtml');
        } else if ($this->getRequest()->getPostValue('massaction_prepare_key') == 'pendingorders') {
            $this->setTemplate('Biztech_Inventorysystem::purchaseorders/order_createpo.phtml');
        } else {
            $this->buttonList->remove('save');
            $this->setTemplate('Biztech_Inventorysystem::purchaseorders/addpurchaseorderproduct.phtml');
        }

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
