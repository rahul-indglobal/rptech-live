<?php

/**
 * Manageleaders_Leaders Add New Row Form Admin Block.
 * @category  Managehomeslider_Homepageslider 
 * @package   Managehomeslider_Homepageslider
 * @author    Lalita Rajput
 *
 */

namespace Managehomeslider\Homepageslider\Block\Adminhtml\Grid\Edit;

/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic {

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context,
     * @param \Magento\Framework\Registry $registry,
     * @param \Magento\Framework\Data\FormFactory $formFactory,
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
     * @param \Managehomeslider\Homepageslider\Model\Status $options,
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig, \Managehomeslider\Homepageslider\Model\Status $options, array $data = []
    ) {
        $this->_options = $options;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
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

        $form->setHtmlIdPrefix('wkgrid_');
        if ($model->getEntityId()) {
            $fieldset = $form->addFieldset(
                    'base_fieldset', ['legend' => __('Edit Slider'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                    'base_fieldset', ['legend' => __('Add News'), 'class' => 'fieldset-wide']
            );
        }

        $fieldset->addField(
                'title', 'text', [
            'name' => 'title',
            'label' => __('Title'),
            'id' => 'title',
            'title' => __('Title'),
           
                ]
        );

        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

        $fieldset->addField(
                'content', 'editor', [
            'name' => 'content',
            'label' => __('Content'),
            'style' => 'height:36em;',
            'required' => false,
            'config' => $wysiwygConfig
                ]
        );
        $fieldset->addField(
                'slider_link', 'text', [
            'name' => 'slider_link',
            'label' => __('Slider Link'),
            'id' => 'slider_link',
            'title' => __('Slider Link'),
            'class' => 'required-entry',
            'required' => true,
                ]
        );

        $fieldset->addField(
                'slider_path', 'image', [
            'name' => 'slider_path',
            'label' => __('Slider Image'),
            'id' => 'slider_path',
            'title' => __('Slider Image'),
            'required' => true,
            'note' => 'Allow image type: jpg, jpeg, png(BANNER SIZE : 1288 * 426)'
                ]
        );
	
	$fieldset->addField(
            'sorting_order',
            'text',
            array(
                'name' => 'sorting_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'required' => true,
                'class' => 'validate-number'
            )
);
	
        $fieldset->addField(
                'is_active', 'select', [
            'name' => 'is_active',
            'label' => __('Status'),
            'id' => 'is_active',
            'title' => __('Status'),
            'values' => $this->_options->getOptionArray(),
            'class' => 'status',
            'required' => true,
                ]
        )->setAfterElementHtml('
        <script>
            require([
                 "jquery",
            ], function($){
                $(document).ready(function () {
                console.log("lalita")
                
                    $( "#wkgrid_is_active" ).find("option:selected").removeAttr("selected"); 
                    $( "#wkgrid_is_active" ).val("1").attr( "selected", "true" );
                });
              });
       </script>
    ');
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
