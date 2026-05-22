<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Managesupplier\Edit\Tab;

use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Biztech\Inventorysystem\Model\ManagesupplierFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Registry;

class AssociatedProducts extends Extended implements TabInterface
{

    protected $_bizHeper;
    protected $_backendHelper;
    protected $_coreRegistry = null;
    protected $_backendSession;
    protected $_suppliersFactory;
    protected $_productFactory;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param ProductFactory $productFactory
     * @param Registry $coreRegistry
     * @param BizHelper $bizHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        ProductFactory $productFactory,
        Registry $coreRegistry,
        BizHelper $bizHelper,
        ManagesupplierFactory $suppliersFactory,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_bizHelper = $bizHelper;
        $this->_backendHelper = $backendHelper;
        $this->_backendSession = $context->getSession();
        $this->_suppliersFactory = $suppliersFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Associated Products');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Associated Products');
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsgrid', ['_current' => true]);
    }

    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    /**
     * @return array
     */
    public function getSelectedProducts()
    {
        $suppliers = $this->getSuppliers();
        $selected = $suppliers->getProducts($suppliers);

        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
    }

    /**
     * @return mixed
     */
    protected function getSuppliers()
    {
        $supplierId = $this->getRequest()->getParam('id') ? $this->getRequest()->getParam('id') : $this->getRequest()->getParam('supplier_id');

        $suppliers = $this->_suppliersFactory->create();
        if ($supplierId) {
            $suppliers->load($supplierId);
        }
        return $suppliers;
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('productsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);

        if ($this->getRequest()->getParam('id') || $this->getRequest()->getParam('supplier_id')) {
            $this->setDefaultFilter(['in_products' => 1]);
        }
    }

    /**
     * @return mixed
     */
    protected function _prepareCollection()
    {
        $websites = $this->_bizHelper->getAllWebsites();

        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'price'
        )->addAttributeToSelect(
            'thumbnail'
        );

        $storeId = (int) $this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $collection->addStoreFilter($storeId);
        }
        $collection->addAttributeToFilter([['attribute' => 'type_id', 'in' => 'simple']]);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return mixed
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'in_products',
            [
            'type' => 'checkbox',
            'name' => 'in_products',
            'values' => $this->_getSelectedProducts(),
            'index' => 'entity_id',
            'header_css_class' => 'col-select col-massaction',
            'column_css_class' => 'col-select col-massaction'
                ]
        );

        $this->addColumn(
            'thumbnail',
            [
            'header' => _('Image'),
            'align' => 'left',
            'index' => 'thumbnail',
            'width' => 97,
            'filter' => false,
            'sortable' => false,
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Pendingitems\Renderer\Image'
                ]
        );
        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);
        $this->addColumn('sku', ['header' => __('SKU'), 'index' => 'sku']);
        $this->addColumn(
            'price',
            [
            'header' => __('Price'),
            'type' => 'currency',
            'currency_code' => (string) $this->_scopeConfig->getValue(
                \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            ),
            'index' => 'price'
                ]
        );

        $this->addColumn(
            'position',
            [
            'header' => __('Postion'),
            'name' => 'position',
            'type' => 'number',
            'validate_class' => 'validate-number',
            'index' => 'position',
            'editable' => !$this->isReadonly(),
            'edit_only' => !$this->getSuppliers()->getId(),
            'header_css_class' => 'col-position',
            'column_css_class' => 'col-position'
                ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return mixed
     */
    protected function _getSelectedProducts()
    {
        $suppliers = $this->getSuppliers();
        return $suppliers->getProducts($suppliers);
    }

    /**
     * @return bool
     */
    protected function isReadonly()
    {
        return false;
    }

    /**
     * @param $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
