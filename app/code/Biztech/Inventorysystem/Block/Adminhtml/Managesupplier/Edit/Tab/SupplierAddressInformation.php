<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Managesupplier\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Directory\Model\CountryFactory as CountryFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollection;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

class SupplierAddressInformation extends Generic implements TabInterface
{
    protected $_systemStore;
    protected $_countryCollection;
    protected $_regionCollection;

    /**
     * @param Context          $context
     * @param Registry         $registry
     * @param FormFactory      $formFactory
     * @param Store            $systemStore
     * @param RegionCollection $regionCollection
     * @param CountryFactory   $countryCollection
     * @param array            $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        RegionCollection $regionCollection,
        CountryFactory $countryCollection,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_countryCollection = $countryCollection;
        $this->_regionCollection = $regionCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Supplier Address Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Supplier Address Information');
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
        $model = $this->_coreRegistry->registry('inventorysystem_supplier_addr_data');
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Supplier Address Information')]);

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'address_line',
            'textarea',
            [
                'name' => 'address_line',
                'label' => __('Street Address'),
                'title' => __('Street Address'),
                'required' => true,
            ]
        );

        $country = $fieldset->addField(
            'country',
            'select',
            [
                'name' => 'country',
                'label' => __('Country'),
                'title' => __('Country'),
                // 'values' => $this->_countryCollection->create()->toOptionArray(),
                'values' => $this->_countryCollection->create()->getResourceCollection()->toOptionArray(),
                'required' => true,
            ]
        );
        /*
         * Add Ajax to the Country select box html output
         */
        $country->setAfterElementHtml("   
            <script type=\"text/javascript\">
                require([
                'jquery',
                'mage/template',
                'jquery/ui',
                'mage/translate'
                ],
                function($, mageTemplate) {
                 $('#edit_form').on('change', '#page_country', function(event){
                    $.ajax({
                     url : '" . $this->getUrl('inventorysystem/*/state') . "country/' +  $('#page_country').val(),
                     type: 'get',
                     dataType: 'json',
                     showLoader:true,
                     success: function(data){
                        regionIdElement = $('#page_state');
                        regionControl = regionIdElement.parent();
                        if (data.htmlconent.length) {
                            if (regionIdElement.prop('tagName').toLowerCase() === 'select') {
                                regionIdElement.empty();
                                regionIdElement.append(data.htmlconent);
                            } else {
                                regionIdInput = $('<select>').attr({
                                    'name': regionIdElement.attr('name'),
                                    'id': regionIdElement.attr('id'),
                                    'class': 'required-entry input-text select',
                                    'title': regionIdElement.attr('title')
                                });

                                regionIdElement.empty();
                                regionIdInput.append(data.htmlconent);
                                regionControl.html(regionIdInput);
                            }
                        } else {
                            regionIdElement.empty();

                            regionInput = $('<input>').attr({
                                'type': 'text',
                                'name': regionIdElement.attr('name'),
                                'id': regionIdElement.attr('id'),
                                'class': 'input-text',
                                'title': regionIdElement.attr('title')
                            });

                            regionControl.html(regionInput);
                        }
                    }
                });
            })
        }
        );
    </script>");

        if ($model->getCountry()) {
            $regionCollection = $this->_countryCollection->create()->setId($model->getCountry())->getLoadedRegionCollection()->toOptionArray();
            if (!empty($regionCollection)) {
                $fieldset->addField(
                    'state',
                    'select',
                    [
                        'name' => 'state',
                        'label' => __('State'),
                        'title' => __('State'),
                        'values' => $this->_countryCollection->create()->setId(
                            $model->getCountry()
                        )->getLoadedRegionCollection()->toOptionArray(),
                        'required' => true,
                    ]
                );
            } else {
                $fieldset->addField(
                    'state',
                    'text',
                    [
                        'name' => 'state',
                        'label' => __('State'),
                        'title' => __('State'),
                        // 'required' => true,
                    ]
                );
            }
        } else {
            $fieldset->addField(
                'state',
                'text',
                [
                    'name' => 'state',
                    'label' => __('State'),
                    'title' => __('State'),
                    // 'required' => true,
                ]
            );
        }

        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'postal_code',
            'text',
            [
                'name' => 'postal_code',
                'label' => __('Postal Code'),
                'title' => __('Postal Code'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'telephone',
            'text',
            [
                'name' => 'telephone',
                'label' => __('Telephone'),
                'title' => __('Telephone'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'fax',
            'text',
            [
                'name' => 'fax',
                'label' => __('Fax'),
                'title' => __('Fax'),
            ]
        );
        /* {{CedAddFormField}} */

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model);
        $this->setForm($form);

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
