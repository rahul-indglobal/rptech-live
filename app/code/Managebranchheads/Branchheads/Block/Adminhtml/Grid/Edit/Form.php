<?php
/**
 * Branchheads Add New Row Form Admin Block.
 * @category  Branchheads 
 * @package   Managebranchheads_Branchheads
 * @author    Lalita Rajput
 *
 */
namespace Managebranchheads\Branchheads\Block\Adminhtml\Grid\Edit;

/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
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
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Managebranchheads\Branchheads\Model\Status $options,
        array $data = []
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
    protected function _prepareForm()
    {
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
                'base_fieldset',
                ['legend' => __('Edit Branch Head'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Branch Head'), 'class' => 'fieldset-wide']
            );
        }

        $fieldset->addField(
            'title',
            'text',
            [
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
            'content',
            'editor',
            [
                'name' => 'content',
                'label' => __('Content'),
                'style' => 'height:36em;',
                'required' => false,
                'config' => $wysiwygConfig
            ]
        );
        
         $fieldset->addField(
            'profile_photo',
            'image',
            [
                'name' => 'profile_photo',
                'label' => __('User Image'),
                'id' => 'profile_photo',
                'title' => __('User Image'),
                'required' => true,
                'note' => 'Allow image type: jpg, jpeg, png'
            ]
        );

//        $fieldset->addField(
//            'publish_date',
//            'date',
//            [
//                'name' => 'publish_date',
//                'label' => __('Publish Date'),
//                'date_format' => $dateFormat,
//                'time_format' => 'H:mm:ss',
//                'class' => 'validate-date validate-date-range date-range-custom_theme-from',
//                'class' => 'required-entry',
//                'style' => 'width:200px',
//            ]
//        );
        $fieldset->addField(
            'is_active',
            'select',
            [
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
