<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Stockreceived;

use Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{

    /**
     * @return mixed
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            array(
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', ['id' => $this->getRequest()->getParam('id')]),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare columns
     * @return Void
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', [
            'header' => __('ID'),
            // 'type' => 'number',
            'filter_index' => 'main_table.id',
            'index' => 'id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);

        parent::_prepareColumns();
    }
}
