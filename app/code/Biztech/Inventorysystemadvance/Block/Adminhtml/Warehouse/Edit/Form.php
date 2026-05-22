<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * Customer Service.
     *
     * @var CustomerAccountServiceInterface
     */
    protected $_customerAccountService;

    /**
     * Prepare form
     * @return object
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            array(
                    'data' => array(
                        'id' => 'edit_form',
                        'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
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
