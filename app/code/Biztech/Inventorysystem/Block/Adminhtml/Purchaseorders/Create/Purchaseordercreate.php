<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Create;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Biztech\Inventorysystem\Helper\Data;

class Purchaseordercreate extends Extended
{

    protected $_productConfig;
    protected $_productCollection;
    protected $_bizHelper;

    /**
     * @param Context       $context
     * @param BackendHelper $backendHelper
     * @param Collection    $productCollection
     * @param Config        $productConfig
     * @param Data          $bizHelper
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        Collection $productCollection,
        Config $productConfig,
        Data $bizHelper
    ) {
        parent::__construct($context, $backendHelper);
        $this->_productCollection = $productCollection;
        $this->_productConfig = $productConfig;
        $this->_bizHelper = $bizHelper;
    }

    /**
     * @return Void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('purchaseorder_create_product_grid');
        $this->setSaveParametersInSession(true);
        if ($this->getRequest()->getParam('collapse')) {
            $this->setIsCollapsed(true);
        }
        $this->setUseAjax(false);
    }

    /**
     * Prepare collection
     * @return Object
     */
    protected function _prepareCollection()
    {
        $collection = $this->_productCollection
                ->addAttributeToSelect($this->_productConfig->getProductAttributes())
                ->addAttributeToSelect('cost')
                ->addAttributeToFilter('type_id', array('nin' => array('configurable', 'grouped')));

        $collection->joinField('qty', 'cataloginventory_stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_products', array(
            'header' => __('Select'),
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_products[]',
            'values' => $this->_getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id',
            'sortable' => false,
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ));

        $this->addColumn(
            'entity_id',
            [
            'header' => __('ID'),
            'type' => 'number',
            'index' => 'entity_id',
            'width' => '60',
                /* 'header_css_class' => 'col-id',
                  'column_css_class' => 'col-id' */
                ]
        );


        $this->addColumn('name', [
            'header' => __('Name'),
            'index' => 'name',
            'class' => 'xxx'
        ]);

        $this->addColumn('sku', [
            'header' => __('SKU'),
            'width' => '80',
            'index' => 'sku',
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer\Create'
        ]);

        $this->addColumn(
            'qty',
            [
            'header' => __('Quantity Available'),
            'type' => 'number',
            'index' => 'qty',
            'width' => '10px',
            'filter' => false,
            'sortable' => false,
                ]
        );


        $this->addColumn(
            'supplier',
            [
            'header' => __('Supplier'),
            'index' => 'supplier',
            'width' => '100px',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer\Supplier'
                ]
        );
        if ($this->_bizHelper->isModuleEnabled('Biztech_Inventorysystemadvance')) {
            $this->addColumn(
                'warehouse',
                [
                'header' => __('Warehouse'),
                'index' => 'warehouse',
                'width' => '100px',
                'filter' => false,
                'sortable' => false,
                'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer\Warehouse'
                    ]
            );
        }
        $this->addColumn('cost', array(
            'header' => __('Unit Cost'),
            'column_css_class' => 'price',
            'align' => 'center',
            'width' => '100px',
            'type' => 'currency',
            'currency_code' => $this->_getStore()->getCurrentCurrencyCode(),
            'index' => 'cost',
            //'renderer'  => 'adminhtml/sales_order_create_search_grid_renderer_price',
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer\Create',
        ));
        $this->addColumn('row_total', array(
            'header' => __('Row Total'),
            'width' => '10px',
            'index' => 'row_total',
            'currency_code' => $this->_getStore()->getCurrentCurrencyCode(),
            'filter' => false,
            'sortable' => false,
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer\Create'
        ));
    }

    /**
     * Selected products for PO
     * @return Array
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('products', []);
        return $products;
    }

    /**
     * Get store
     * @return Object
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * Po collection
     * @return Object
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->addOptionsToResult();
        return parent::_afterLoadCollection();
    }
}
