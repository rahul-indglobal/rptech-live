<?php

/**
 * Manageevents_Events Add New Row Form Admin Block.
 * @category  Manageevents_Events 
 * @package   Manageevents_Events
 * @author    Lalita Rajput
 *
 */

namespace Manageevents\Events\Block\Adminhtml\Grid\Edit;

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
     * @param \Webkul\Grid\Model\Status $options,
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig, \Manageevents\Events\Model\Status $options, array $data = []
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
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('tbl_cities'); //gives table name with prefix

        $sql = "Select id , city_name  FROM " . $tableName;
        $resultcity = $connection->fetchAll($sql);
        foreach($resultcity as $val){
            $cities[ $val['city_name']] = $val['city_name'];
        }
       // echo "<pre>";print_r($brands);die;
        
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
                    'base_fieldset', ['legend' => __('Edit Events'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                    'base_fieldset', ['legend' => __('Add Events'), 'class' => 'fieldset-wide']
            );
        }

        $fieldset->addField(
                'title', 'text', [
            'name' => 'title',
            'label' => __('Title'),
            'id' => 'title',
            'title' => __('Title'),
            'class' => 'required-entry',
            'required' => true,
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
                'city', 'select', [
            'name' => 'city',
            'label' => __('City'),
            'id' => 'city',
            'title' => __('City'),
            'values' =>$cities,
            'class' => 'status',
            'required' => false,
                ]
        );

        $fieldset->addField(
                'event_image_1', 'image', [
            'name' => 'event_image_1',
            'label' => __('Event Image 1'),
            'id' => 'event_image_1',
            'title' => __('Event Image 1'),
                    'class' => 'required-entry',
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );
        $fieldset->addField(
                'event_image_2', 'image', [
            'name' => 'event_image_2',
            'label' => __('Event Image 2'),
            'id' => 'event_image_2',
            'title' => __('Event Image 2'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'event_image_3', 'image', [
            'name' => 'event_image_3',
            'label' => __('Event Image 3'),
            'id' => 'event_image_3',
            'title' => __('Event Image 3'),
             'class' => 'required-entry',
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'event_image_4', 'image', [
            'name' => 'event_image_4',
            'label' => __('Event Image 4'),
            'id' => 'event_image_4',
            'title' => __('Event Image 4'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );
        $fieldset->addField(
                'event_image_5', 'image', [
            'name' => 'event_image_5',
            'label' => __('Event Image 5'),
            'id' => 'event_image_5',
            'title' => __('Event Image 5'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'event_image_6', 'image', [
            'name' => 'event_image_6',
            'label' => __('Event Image 6'),
            'id' => 'event_image_6',
            'title' => __('Event Image 6'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'event_image_7', 'image', [
            'name' => 'event_image_7',
            'label' => __('Event Image 7'),
            'id' => 'event_image_7',
            'title' => __('Event Image 7'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );
        $fieldset->addField(
                'event_image_8', 'image', [
            'name' => 'event_image_8',
            'label' => __('Event Image 8'),
            'id' => 'event_image_8',
            'title' => __('Event Image 8'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'event_image_9', 'image', [
            'name' => 'event_image_9',
            'label' => __('Event Image 9'),
            'id' => 'event_image_9',
            'title' => __('Event Image 9'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'event_image_10', 'image', [
            'name' => 'event_image_10',
            'label' => __('Event Image 10'),
            'id' => 'event_image_10',
            'title' => __('Event Image 10'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );


        $fieldset->addField(
                'publish_date', 'date', [
            'name' => 'publish_date',
            'label' => __('Event Date'),
            'date_format' => $dateFormat,
            'time_format' => 'H:mm:ss',
            'class' => 'validate-date validate-date-range date-range-custom_theme-from',
            'class' => 'required-entry',
            'style' => 'width:200px',
                ]
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
