<?php

namespace Rptech\QuantumRma\Block\Adminhtml\QuantumRma\Edit;

use Magento\Framework\Exception\LocalizedException;
use Rptech\QuantumRma\Model\Source\Config\Status;

/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic {

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Status $status,
        array $data = []
    ) {
        $this->status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareForm() {

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'enctype' => 'multipart/form-data',
                'action' => $this->getData('action'),
                'method' => 'post'
            ]
            ]
        );

        $form->setHtmlIdPrefix('');
        if ($model->getId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset', ['legend' => __('Edit Form'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset', ['legend' => __('Add Form'), 'class' => 'fieldset-wide']
            );
        }
        $formData = $model->getData();
        $fieldset->addField(
            'status', 'select', [
                'name' => 'status',
                'label' => __('Status'),
                'id' => 'status',
                'title' => __('Status'),
                'class' => 'required-entry',
                'values' => $this->status->toOptionArray(),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'date_time', 'date', [
                'name' => 'date_time',
                'label' => __('Date Time'),
                'id' => 'date_time',
                'title' => __('Date Time'),
                'date_format' => $dateFormat
            ]
        );

        $fieldset->addField(
            'remarks', 'textarea', [
                'name' => 'remarks',
                'label' => __('Remarks'),
                'id' => 'remarks',
                'title' => __('Remarks')
            ]
        );

        $form->setValues($formData);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
