<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Edit\Tab;

use Magento\Backend\Block\Template\Context;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $registry;
    protected $formFactory;

    /**
     * @param Context                             $context
     * @param \Magento\Framework\Registry         $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array                               $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->registry = $registry;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Upload CSV');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Upload CSV');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form
     * @return Void
     */
    protected function _prepareForm()
    {
        $model = $this->registry->registry('inventorysystem_data');
        $isElementDisabled = false;
        $form = $this->formFactory->create();
        $this->setForm($form);
        $fieldset = $form->addFieldset('inventorysystem_form', array('legend' => __('Upload CSV')));

        $fieldset->addField('csvfilename', 'file', array(
            'label' => __('Upload CSV File'),
            'required' => false,
            'name' => 'csvfilename',
            'note' => 'Allowed file type: csv',
        ));

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        if ($model) {
            $form->setValues($model->getData());
            $this->setForm($form);
        }


        return parent::_prepareForm();
    }

    /**
     * Is allowed
     * @param  int  $resourceId
     * @return boolean
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
