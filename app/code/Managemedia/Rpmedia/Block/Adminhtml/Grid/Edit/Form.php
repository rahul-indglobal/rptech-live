<?php

/**
 * Managemedia\Rpmedia Add New Row Form Admin Block.
 * @category  Managemedia\Rpmedia
 * @package   Managemedia\Rpmedia
 * @author    Lalita Rajput
 *
 */

namespace Managemedia\Rpmedia\Block\Adminhtml\Grid\Edit;

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
     * @param \Managemevent\Rpmedia\Model\Status $options,
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig, \Managemedia\Rpmedia\Model\Status $options, array $data = []
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
        $tableName = $resource->getTableName('ves_brand'); //gives table name with prefix

        $sql = "Select brand_id , name FROM " . $tableName;
        $resultBrands = $connection->fetchAll($sql);
        foreach ($resultBrands as $val) {
            $brands[$val['brand_id']] = $val['name'];
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
                    'base_fieldset', ['legend' => __('Edit Media'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                    'base_fieldset', ['legend' => __('Add Media'), 'class' => 'fieldset-wide']
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
                'link_url', 'text', [
            'name' => 'link_url',
            'label' => __('Link URL'),
            'id' => 'link_url',
            'title' => __('Link URL'),
           // 'class' => 'required-entry',
          //  'required' => true,
                ]
        );
        $fieldset->addField(
                'brand_id', 'select', [
            'name' => 'brand_id',
            'label' => __('Brand'),
            'id' => 'brand_id',
            'title' => __('Brand'),
            'values' => $brands,
            'class' => 'status',
            'required' => false,
                ]
        );

        $fieldset->addField(
                'publish_date', 'date', [
            'name' => 'publish_date',
            'label' => __('Media Date'),
            'date_format' => $dateFormat,
            'time_format' => 'H:mm:ss',
            'class' => 'validate-date validate-date-range date-range-custom_theme-from',
            'class' => 'required-entry',
            'style' => 'width:200px',
			'required' => true,
                ]
        );
	 $fieldset->addField(
                'media_image_1', 'image', [
            'name' => 'media_image_1',
            'label' => __('News Image 1'),
            'id' => 'media_image_1',
            'title' => __('News Image 1'),
                    'class' => 'required-entry',
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );
        $fieldset->addField(
                'media_image_2', 'image', [
            'name' => 'media_image_2',
            'label' => __('News Image 2'),
            'id' => 'media_image_2',
            'title' => __('News Image 2'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'media_image_3', 'image', [
            'name' => 'media_image_3',
            'label' => __('News Image 3'),
            'id' => 'media_image_3',
            'title' => __('News Image 3'),
             'class' => 'required-entry',
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'media_image_4', 'image', [
            'name' => 'media_image_4',
            'label' => __('News Image 4'),
            'id' => 'media_image_4',
            'title' => __('News Image 4'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );
        $fieldset->addField(
                'media_image_5', 'image', [
            'name' => 'media_image_5',
            'label' => __('News Image 5'),
            'id' => 'media_image_5',
            'title' => __('News Image 5'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
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
