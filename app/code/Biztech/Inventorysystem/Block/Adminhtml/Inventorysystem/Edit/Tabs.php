<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('inventorysystem_tabs');
        $this->setDestElementId('edit_form');
    }

    /**
     * @return $this
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => __('Upload CSV'),
            'title' => __('Upload CSV'),
            'content' => $this->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Edit\Tab\Form')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}
