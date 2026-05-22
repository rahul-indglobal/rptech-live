<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Managesupplier\Edit\Tab;

use Biztech\Inventorysystem\Model\ResourceModel\Managesupplier\Status;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

class SupplierInformation extends Generic implements TabInterface
{
    protected $_systemStore;
    protected $_supplierStatus;

    /**
     * @param Context     $context
     * @param Registry    $registry
     * @param FormFactory $formFactory
     * @param Store       $systemStore
     * @param Status      $supplierStatus
     * @param array       $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Status $supplierStatus,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_supplierStatus = $supplierStatus;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Supplier Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Supplier Information');
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
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Page */
        //$model = $this->_coreRegistry->registry('inventorysystem_managesupplier');
        $model = $this->_coreRegistry->registry('inventorysystem_supplier_data');
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        //$form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('Supplier Information')));

        if ($model->getId()) {
            $fieldset->addField('supplier_id', 'hidden', array('name' => 'supplier_id'));
        }

        $fieldset->addField(
            'first_name',
            'text',
            array(
                'name' => 'first_name',
                'label' => __('First Name'),
                'title' => __('First Name'),
                'required' => true,
            )
        );
        $fieldset->addField(
            'last_name',
            'text',
            array(
                'name' => 'last_name',
                'label' => __('Last Name'),
                'title' => __('Last Name'),
                'required' => true,
            )
        );
        $fieldset->addField(
            'email',
            'text',
            array(
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
                'required' => true,
                'class' => 'avalidate-email'
            )
        );
        if ($model->getId()) {
            // Add password management fieldset
            $newFieldset = $form->addFieldset('password_fieldset', array('legend' => __('Password Management')));
            // New customer password
            $field = $newFieldset->addField(
                'new_password',
                'password',
                array(
                    'name' => 'new_password',
                    'label' => __('New Password'),
                    'title' => __('New Password'),
                    'class' => 'validate-new-password'
                )
            );
        } else {
            $fieldset->addField(
                'password',
                'password',
                [
                    'name' => 'password',
                    'label' => __('Password'),
                    'title' => __('Password'),
                    'class' => 'validate-admin-password admin__control-text',
                    'required' => true,
                ]
            );
        }
        $fieldset->addField(
            'company',
            'text',
            array(
                'name' => 'company',
                'label' => __('Company'),
                'title' => __('Company'),
                'required' => true,
            )
        );
        $fieldset->addField(
            'contact_person',
            'text',
            array(
                'name' => 'contact_person',
                'label' => __('Contact Person'),
                'title' => __('Contact Person'),
            )
        );
        $fieldset->addField(
            'shipment_method',
            'text',
            array(
                'name' => 'shipment_method',
                'label' => __('Shipping Method'),
                'title' => __('Shipping Method'),
            )
        );
        $fieldset->addField(
            'payment_method',
            'textarea',
            array(
                'name' => 'payment_method',
                'label' => __('Payment Method'),
                'title' => __('Payment Method'),
            )
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'name' => 'is_active',
                'label' => __('Status'),
                'title' => __('Status'),
                'values' => $this->_supplierStatus->toOptionArray()
            ]
        );

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
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
