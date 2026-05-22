<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Edit\Tab;

use Magento\Directory\Model\CountryFactory as CountryFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollection;

class WarehouseInformation extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_systemStore;
    protected $_inventorysystemadvanceHelper;
    protected $_countryCollection;
    protected $_regionCollection;

    /**
     * @param \Magento\Backend\Block\Template\Context     $context
     * @param \Magento\Framework\Registry                 $registry
     * @param \Magento\Framework\Data\FormFactory         $formFactory
     * @param \Magento\Store\Model\System\Store           $systemStore
     * @param RegionCollection                            $regionCollection
     * @param CountryFactory                              $countryCollection
     * @param \Biztech\Inventorysystemadvance\Helper\Data $inventorysystemadvanceHelper
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        RegionCollection $regionCollection,
        CountryFactory $countryCollection,
        \Biztech\Inventorysystemadvance\Helper\Data $inventorysystemadvanceHelper,
        array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        $this->_countryCollection = $countryCollection;
        $this->_regionCollection = $regionCollection;
        $this->_inventorysystemadvanceHelper = $inventorysystemadvanceHelper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $getStateID = '';

        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('warehouse_data');
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        //$form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('Warehouse Information')));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array('name' => 'id'));
        }

        $fieldset->addField(
            'warehouse_name',
            'text',
            array(
            'name' => 'warehouse_name',
            'label' => __('Warehouse Name'),
            'title' => __('Warehouse Name'),
            'required' => true,
                )
        );
        $fieldset->addField(
            'telephone',
            'text',
            array(
            'name' => 'telephone',
            'label' => __('Telephone'),
            'title' => __('Telephone'),
            'required' => true,
                )
        );
        $fieldset->addField(
            'street',
            'text',
            array(
            'name' => 'street',
            'label' => __('Street'),
            'title' => __('Street'),
            'required' => true,
                )
        );
        $fieldset->addField(
            'city',
            'text',
            array(
            'name' => 'city',
            'label' => __('City'),
            'title' => __('City'),
            'required' => true,
                )
        );
        $country = $fieldset->addField(
            'country',
            'select',
            [
            'name' => 'country',
            'label' => __('Country'),
            'title' => __('Country'),
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
                 $('#edit_form').on('change', '#country', function(event){
                    $.ajax({
                     url : '" . $this->getUrl('inventorysystemadvance/*/state') . "country/' +  $('#country').val(),
                     type: 'get',
                     dataType: 'json',
                     showLoader:true,
                     success: function(data){
                        regionIdElement = $('#state');
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
                                'class': 'input-text letters-only',
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

        if ($model->getStateId() !== null) {
            $getStateID = $model->getStateId();
        }

        $warehouseId = $this->getRequest()->getParam('id');
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
                        'class' => 'letters-only',
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
                    'class' => 'letters-only',
                ]
            );
        }

        $fieldset->addField(
            'postal_code',
            'text',
            array(
            'name' => 'postal_code',
            'label' => __('Postal Code'),
            'title' => __('Postal Code'),
            'required' => true,
                )
        );
        $fieldset->addField(
            'status',
            'select',
            ['name' => 'status', 'label' => __('Status'), 'title' => __('Status'), 'values' => array(
                array(
                    'value' => 1,
                    'label' => __('Enable')
                ),
                array(
                    'value' => 0,
                    'label' => __('Disable')
                ))
                ]
        );
        $primaryWarehouse = $fieldset->addField(
            'primary_warehouse',
            'select',
            ['name' => 'primary_warehouse', 'label' => __('Primary Warehouse'), 'title' => __('Primary Warehouse'), 'values' => array(
                array(
                    'value' => 1,
                    'label' => __('Yes')
                ),
                array(
                    'value' => 0,
                    'label' => __('No')
                ))
                ]
        );
        $primaryWarehouse->setAfterElementHtml("<script type=\"text/javascript\"> document.getElementById('primary_warehouse').disabled = true;</script>");
        /* {{CedAddFormField}} */

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Warehouse Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Warehouse Information');
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
