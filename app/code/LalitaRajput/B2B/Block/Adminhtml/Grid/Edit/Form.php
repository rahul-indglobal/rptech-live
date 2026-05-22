<?php
/**
 * Webkul_Grid Add New Row Form Admin Block.
 * @category    Webkul
 * @package     Webkul_Grid
 * @author      Webkul Software Private Limited
 *
 */
namespace LalitaRajput\B2B\Block\Adminhtml\Grid\Edit;
 
 
/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{


    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_countryCollectionFactory;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        //\LalitaRajput\B2B\Model\Status $options,
        array $data = []
    ) 
    {
        //$this->_options = $options;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_countryCollectionFactory = $countryCollectionFactory;




    }


    public function getCountryCollection()
    {
        $collection = $this->_countryCollectionFactory->create()->loadByStore();
        return $collection;
    }
 
    /**
     * Retrieve list of top destinations countries
     *
     * @return array
     */
    protected function getTopDestinations()
    {
        $destinations = (string)$this->_scopeConfig->getValue(
            'general/country/destinations',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return !empty($destinations) ? explode(',', $destinations) : [];
    }

    /**
     * Retrieve list of countries in array option
     *
     * @return array
     */
    public function getCountries()
    {
        return $options = $this->getCountryCollection()
                ->setForegroundCountries($this->getTopDestinations())
                    ->toOptionArray();
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


        $fieldset = $form->addFieldset(
                'product_fieldset',
                ['legend' => __('Add Product'), 'class' => 'fieldset-wide']
            );

        // $fieldset->addField('registered1', 'button', array(
        //     'label' => __('Send e-mail to all registered customers'),
        //     'value' => __('test'),
        //     'name'  => 'registered',
        //     'class' => 'form-button',
        //     'onclick' => "setLocation('{$this->getUrl('*/*/registeremail')}')",
        // ));


        $fieldset->addField(
            'product_name',
            'text',
            [
                'name' => 'product_name',
                'label' => __('product_name'),
                'id' => 'product_name',
                'title' => __('Title'),
                'class' => 'status',
                'required' => true,
            ]
        );



        $fieldset = $form->addFieldset(
                'accountinfo_fieldset',
                ['legend' => __('Account Information'), 'class' => 'fieldset-wide']
            );

        $fieldset->addField(
            'group',
            'select',
            [
                'name' => 'group',
                'label' => __('Group'),
                'id' => 'group',
                'title' => __('Title'),
                'values' => ['4' => __('B2B')],
                'class' => 'status',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'id' => 'email',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

 
        //$form->setHtmlIdPrefix('wkgrid_');
        if ($model->getEntityId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Billing Address'), 'class' => 'fieldset-wide']
            );
        }
 
        $fieldset->addField(
            'name_prefix',
            'text',
            [
                'name' => 'name-prefix',
                'label' => __('Name Prefix'),
                'id' => 'title',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );
 

        $fieldset->addField(
            'first_name',
            'text',
            [
                'name' => 'first_name',
                'label' => __('First Name'),
                'id' => 'first_name',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'middle_name',
            'text',
            [
                'name' => 'middle_name',
                'label' => __('Middle Name/Initial'),
                'id' => 'middle_name',
                'title' => __('Title'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );

        $fieldset->addField(
            'last_name',
            'text',
            [
                'name' => 'last_name',
                'label' => __('Last Name'),
                'id' => 'last_name',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'company',
            'text',
            [
                'name' => 'company',
                'label' => __('Company'),
                'id' => 'company',
                'title' => __('Title'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );

        $fieldset->addField(
            'street_address',
            'text',
            [
                'name' => 'street_address',
                'label' => __('Street Address'),
                'id' => 'street_address',
                'title' => __('street_address'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('city'),
                'id' => 'city',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $country = $fieldset->addField('country', 'select', array(
            'name'     => 'country',
            'label'    => 'Country',
            'id'       => 'country',
            'values'   => $this->getCountries(),
            'class' => 'required-entry',
            'required' => true,
            'onchange' => 'getstate(this)',
        ));

        $fieldset->addField('state', 'select', array(
            'name'  => 'state',
            'label' => 'State',
            'id' => 'state',
            'title' => __('Title'),
            //'class' => 'required-entry',
            //'required' => false,
        ));
        

         /*
         * Add Ajax to the Country select box html output
         */
        $country->setAfterElementHtml("<script type=\"text/javascript\">
            function getstate(selectElement){
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if(this.responseText.length > 0){
                            console.log(this.responseText);
                            var stateElement = document.getElementById('state');
                            if(stateElement.nodeName == 'SELECT'){
                                document.getElementById('state').innerHTML = this.responseText;
                            }else{
                                document.getElementById('state').remove();
                                var input = document.createElement('select');
                                input.name = 'state';
                                input.id = 'state';
                                input.setAttribute('class', 'required-entry required-entry _required select admin__control-select');
                                input.setAttribute('style', 'margin-left: 30px;');
                                document.getElementsByClassName('field-state')[0].appendChild(input);
                                document.getElementById('state').innerHTML = this.responseText;
                            }    
                        }else{
                            document.getElementById('state').remove();
                            var input = document.createElement('input');
                            input.name = 'state';
                            input.id = 'state';
                            input.setAttribute('class', 'required-entry input-text admin__control-text required-entry _required');
                            input.setAttribute('style', 'margin-left: 30px;padding-left: 509px;');
                            document.getElementsByClassName('field-state')[0].appendChild(input);
                        }
                    }
                  };
                  xhttp.open('GET', 'http://10.0.2.37/m2_rptech/getStates.php?id=' + selectElement.value, true);
                  xhttp.send();
            }
        </script>");

        $fieldset->addField(
            'postal_code',
            'text',
            [
                'name' => 'postal_code',
                'label' => __('Zip/Postal Code'),
                'id' => 'postal_code',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'phone_number',
            'text',
            [
                'name' => 'phone_number',
                'label' => __('Phone Number'),
                'id' => 'phone_number',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'fax',
            'text',
            [
                'name' => 'fax',
                'label' => __('Fax'),
                'id' => 'fax',
                'title' => __('Title'),
                //'class' => 'required-entry',
                //'required' => true,
            ]
        );

        $fieldset->addField(
            'gst_number',
            'text',
            [
                'name' => 'gst_number',
                'label' => __('GST Number'),
                'id' => 'gst_number',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );


        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
}
?>

