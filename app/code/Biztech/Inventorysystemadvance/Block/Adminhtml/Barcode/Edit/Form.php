<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected $_customerAccountService;

    /**
     * prepareForm
     * @return void
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            array(
                    'data' => array(
                        'id' => 'edit_form',
                        'action' => $this->getUrl('*/*/save'),
                        'method' => 'post',
                        'enctype' => 'multipart/form-data'
                    )
                )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
