<?php
/**
 * Webkul_Grid Add New Row Form Admin Block.
 * @category    Webkul
 * @package     Webkul_Grid
 * @author      Webkul Software Private Limited
 *
 */
namespace Webkul\Grid\Block\Adminhtml\Grid\Edit;

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
        \Webkul\Grid\Model\Status $options,
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
                ['legend' => __('Edit News'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add News'), 'class' => 'fieldset-wide']
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
            'news_link',
            'text',
            [
                'name' => 'news_link',
                'label' => __('News Link'),
                'id' => 'news_link',
                'title' => __('News Link'),
              //  'class' => 'required-entry',
              //  'required' => true,
            ]
        );
           $fieldset->addField(
                'news_image_1', 'image', [
            'name' => 'news_image_1',
            'label' => __('News Image 1'),
            'id' => 'news_image_1',
            'title' => __('News Image 1'),
                    'class' => 'required-entry',
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );
        $fieldset->addField(
                'news_image_2', 'image', [
            'name' => 'news_image_2',
            'label' => __('News Image 2'),
            'id' => 'news_image_2',
            'title' => __('News Image 2'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'news_image_3', 'image', [
            'name' => 'news_image_3',
            'label' => __('News Image 3'),
            'id' => 'news_image_3',
            'title' => __('News Image 3'),
             'class' => 'required-entry',
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );

        $fieldset->addField(
                'news_image_4', 'image', [
            'name' => 'news_image_4',
            'label' => __('News Image 4'),
            'id' => 'news_image_4',
            'title' => __('News Image 4'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );
        $fieldset->addField(
                'news_image_5', 'image', [
            'name' => 'news_image_5',
            'label' => __('News Image 5'),
            'id' => 'news_image_5',
            'title' => __('News Image 5'),
            'required' => FALSE,
            'note' => 'Allow image type: jpg, jpeg, png'
                ]
        );
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
