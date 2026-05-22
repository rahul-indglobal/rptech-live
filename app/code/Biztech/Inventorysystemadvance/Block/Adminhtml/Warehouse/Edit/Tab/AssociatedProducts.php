<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Edit\Tab;

use Biztech\Inventorysystemadvance\Model\WarehouseFactory;

class AssociatedProducts extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $productCollectionFactory;
    protected $WarehouseFactory;
    protected $registry;
    protected $storeManager;
    protected $inventorysystemHelper;
    protected $_objectManager = null;
    protected $_resourceConnection;
    protected $_warehouseModel;

    /**
     * @param \Magento\Backend\Block\Template\Context                        $context
     * @param \Magento\Backend\Helper\Data                                   $backendHelper
     * @param \Magento\Framework\Registry                                    $registry
     * @param \Magento\Framework\ObjectManagerInterface                      $objectManager
     * @param WarehouseFactory                                               $WarehouseFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Biztech\Inventorysystem\Helper\Data                           $inventorysystemHelper
     * @param \Magento\Framework\App\ResourceConnection                      $resourceConnection
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse                $warehouseModel
     * @param array                                                          $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        WarehouseFactory $WarehouseFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        array $data = []
    ) {
        $this->WarehouseFactory = $WarehouseFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_objectManager = $objectManager;
        $this->registry = $registry;
        $this->storeManager = $context->getStoreManager();
        $this->inventorysystemHelper = $inventorysystemHelper;
        $this->_resourceConnection = $resourceConnection;
        $this->_warehouseModel = $warehouseModel;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('productsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('id')) {
            $this->setDefaultFilter(array('in_product' => 1));
        }
    }

    /**
     * add Column Filter To Collection
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_product') {
            $productIds = $this->_getSelectedProducts();

            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * prepare collection
     */
    protected function _prepareCollection()
    {
        $websites = $this->inventorysystemHelper->getAllWebsites();
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('price')
                ->addAttributeToSelect('thumbnail');
        //$collection->addFieldToFilter('type_id', Array('eq' => 'simple'));
        $collection->addAttributeToFilter(array(array('attribute' => 'type_id', 'nin' => array('grouped', 'configurable', 'bundle'))));
        $adminStore = $this->_storeManager->getStore()->getStoreId();
        $collection->joinAttribute('product_name', 'catalog_product/name', 'entity_id', null, 'left', $adminStore);
        if ($this->getWarehouse()->getId()) {
            $constraint = '{{table}}.warehouse_id=' . $this->getWarehouse()->getId();
        } else {
            $constraint = '{{table}}.rel_id=0';
        }
        $this->_resources = $this->_resourceConnection;
        $connection1 = $this->_resources->getConnection();
        $collection->joinField('position', $connection1->getTableName('bc_warehouse_product_is'), 'position', 'product_id=entity_id', $constraint, 'left');

        $collection->joinField('qty', $connection1->getTableName('cataloginventory_stock_item'), 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        $collection->joinField('quantity', $connection1->getTableName('bc_warehouse_product_is'), 'quantity', 'product_id=entity_id', $constraint, 'left');
        $collection->addWebsiteFilter($websites);
        $collection->getSelect()->group('e.entity_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        
        $model = $this->_warehouseModel;

        $this->addColumn(
            'in_product',
            [
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_product',
            'align' => 'center',
            'index' => 'entity_id',
            'values' => $this->_getSelectedProducts(),
                ]
        );
        $this->addColumn(
            'thumbnail',
            [
            'header' => _('Image'),
            'align' => 'left',
            'index' => 'thumbnail',
            'width' => '100px',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Pendingitems\Renderer\Image'
                ]
        );
        $this->addColumn(
            'name',
            [
            'header' => __('Name'),
            'index' => 'name',
            'class' => 'xxx',
            'width' => '100px',
                ]
        );
        $this->addColumn(
            'price',
            [
            'header' => __('Price'),
            'type' => 'currency',
            'index' => 'price',
            'width' => '50px',
                ]
        );
        $this->addColumn(
            'sku',
            [
            'header' => __('SKU'),
            'index' => 'sku',
            'class' => 'xxx',
            'width' => '50px',
                ]
        );
        $this->addColumn('qty', array(
            'header' => __('Total Qty'),
            'validate_class' => 'validate-number',
            'index' => 'qty',
            'width' => 1,
            'renderer' => 'Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer\Productqty'
        ));
        $this->addColumn('quantity', array(
            'header' => __('Warhouse Qty.'),
            'name' => 'quantity',
            'width' => 60,
            'type' => 'input',
            'validate_class' => 'validate-number',
            'index' => 'quantity',
            'sortable' => false,
            'filter' => false,
            'renderer' => 'Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer\Productqty'
        ));

        $this->addColumn('total_qty', array(
            'header' => __('Quantity'),
            'width' => '10px',
            'align' => 'center',
            'type' => 'input',
            'index' => 'total_qty',
            'sortable' => false,
            'filter' => false,
            'renderer' => 'Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer\Totalqty'
        ));

        $this->addColumn('qty_level', array(
            'header' => __('Inc/Dec Qty'),
            'index' => 'qty_level',
            'sortable' => false,
            'filter' => false,
            'renderer' => 'Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer\Qtylevel'
        ));

        return parent::_prepareColumns();
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

    protected function _getSelectedProducts()
    {
        $warehouse = $this->getWarehouse();
        return $warehouse->getProducts($warehouse);
    }

    /**
     * Retrieve selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $warehouse = $this->getWarehouse();
        $selected = $warehouse->getProducts($warehouse);

        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
    }

    protected function getWarehouse()
    {
        $warehouseId = $this->getRequest()->getParam('id');
        $warehouse = $this->WarehouseFactory->create();
        if ($warehouseId) {
            $warehouse->load($warehouseId);
        }
        return $warehouse;
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
        return true;
    }
}
