<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Pendingitems;

use Biztech\Inventorysystem\Helper\Data as BiztechHelper;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogInventory\Model\Source\Stock;
use Magento\Framework\Module\Manager;

class Grid extends Extended
{

    protected $_bizHelper;
    protected $_productCollection;
    protected $_productConfig;
    protected $moduleManager;
    protected $_productStatus;
    protected $_stockOptions;
    protected $_resources;
    protected $_resourceConnection;
    protected $_inventorysystemHelper;
    protected $_messageManager;
    protected $_warehouseModel;
    protected $_backendUrl;

    /**
     * @param Context                                         $context
     * @param Data                                            $backendHelper
     * @param BiztechHelper                                   $bizHelper
     * @param Collection                                      $productCollection
     * @param Config                                          $config
     * @param Manager                                         $moduleManager
     * @param Stock                                           $stockOptions
     * @param Status                                          $productStatus
     * @param \Magento\Framework\App\ResourceConnection       $resourceConnection
     * @param \Biztech\Inventorysystem\Helper\Data            $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface     $messageManager
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     * @param \Magento\Backend\Model\UrlInterface             $backendUrl
     * @param array                                           $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        BiztechHelper $bizHelper,
        Collection $productCollection,
        Config $config,
        Manager $moduleManager,
        Stock $stockOptions,
        Status $productStatus,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
        $this->_bizHelper = $bizHelper;
        $this->_productCollection = $productCollection->load();
        $this->_productConfig = $config;
        $this->_stockOptions = $stockOptions;
        $this->_productStatus = $productStatus;
        $this->_resourceConnection = $resourceConnection;
        $this->moduleManager = $moduleManager;
        $this->_inventorysystemHelper = $inventorysystemHelper;
        $this->_messageManager = $messageManager;
        $this->_warehouseModel = $warehouseModel;
        $this->_backendUrl = $backendUrl;
    }

    /**
     * @param $item
     * @return string
     */
    public function getRowClass($item)
    {
        $class = '';

        if ($item->getIsInStock() == "1") {
            $class = 'lowstock';
        } elseif ($item->getIsInStock() == "0") {
            $class = 'nostock';
        }
        return $class;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('pendingitemsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareCollection()
    {
        $websites = $this->_bizHelper->getAllWebsites();
        $minQty = $this->_bizHelper->getConfig('cataloginventory/item_options/min_qty');
        $collection = $this->_productCollection;
        $collection->addAttributeToSelect($this->_productConfig->getProductAttributes());
// $collection->addWebsiteFilter($websites);
        $collection->joinField('qty', 'cataloginventory_stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        $collection->joinField('is_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');


        if ($this->moduleManager->isEnabled('Biztech_Inventorysystemadvance')) {
            $min_warehouse_qty = $this->_bizHelper->getConfig('inventorysystem/inventorysystemadvance/warehouse_level_quantity');
            if ($min_warehouse_qty == "") {
                $min_warehouse_qty = $minQty;
            }
            $collection->joinField('quantity', 'bc_warehouse_product_is', 'quantity', 'product_id=entity_id', '{{table}}.quantity<' . $min_warehouse_qty);
            $collection->getSelect()->group('e.entity_id');
        } else {
            $collection->addAttributeToFilter(array(array('attribute' => 'qty', 'lteq' => $minQty), array('attribute' => 'is_in_stock', 'eq' => 0)));
        }
        $collection->addAttributeToFilter(array(array('attribute' => 'type_id', 'in' => 'simple')));

        /*
         * TODO: To pass filter items to sort the collection.
         */
        /* pass filter to select products for export in csv */
        

        /* Check if extension is enabled with activation key */
        
        $this->inventorysystemHelper = $this->_inventorysystemHelper;
        $this->messageManager = $this->_messageManager;
        if ($this->inventorysystemHelper->isEnable()) {
            $this->setCollection($collection);

            return parent::_prepareCollection();
        } else {
            $this->messageManager->addError(__('Extension- Magemob Inventory is not enabled. Please enable it from Store > Configuration > Biztech > Magemob Inventory.'));
            return $this;
        }
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
            'header' => __('ID'),
            'type' => 'number',
            'index' => 'entity_id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id',
            'style' => 'width:25px;'
                ]
        );

        $this->addColumn('image', [
            'header' => __('Image'),
            'align' => 'left',
            'index' => 'image',
            'width' => '97',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Pendingitems\Renderer\Image'
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'index' => 'name',
            'class' => 'xxx'
        ]);

        $this->addColumn('sku', [
            'header' => __('SKU'),
            'width' => '300px',
            'index' => 'sku'
        ]);

        $store = $this->_getStore();
        $this->addColumn(
            'price',
            [
            'header' => __('Price'),
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
            'height' => '100%',
            'header_css_class' => 'col-price',
            'column_css_class' => 'col-price'
                ]
        );

        $this->addColumn(
            'qty',
            [
            'header' => __('Avail. Qty'),
            'type' => 'number',
            'index' => 'qty'
                ]
        );

        if ($this->moduleManager->isEnabled('Biztech_Inventorysystemadvance')) {
            $this->addColumn('warehouse_qty', array(
                'header' => __('Warehouse Qty'),
                'index' => 'warehouse_qty',
                'filter' => false,
                'sortable' => false,
                'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Pendingitems\Renderer\Warehouseqty'
            ));
        }

        $getStockOptions = $this->_stockOptions->getAllOptions();
        $stockOptions = [];
        for ($i = 0; $i < count($getStockOptions); $i++) {
            $stockOptions[$getStockOptions[$i]['value']] = $getStockOptions[$i]['label'];
        }

        $this->addColumn('is_in_stock', [
            'header' => __('Stock Availability'),
            'width' => '20px',
            'index' => 'is_in_stock',
            'type' => 'options',
            'options' => $stockOptions
        ]);


        $this->addColumn('status', [
            'header' => __('Status'),
            'width' => '90px',
            'index' => 'status',
            'type' => 'options',
            'options' => $this->_productStatus->getOptionArray(),
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * TODO: Massaction call and references
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('pendingitems');

        $this->getMassactionBlock()->addItem('generatePOFromItems', array(
            'label' => __('Generate PO'),
            'url' => $this->getUrl('*/purchaseorders/purchaseorderform', array('_current' => true)),
            'selected' => true
        ));

        $this->getMassactionBlock()->addItem('export_csv', array(
            'label' => __('Export in CSV'),
            'url' => $this->getUrl('*/*/exportCsv', array('_current' => true))
        ));
    }

    /**
     * Pending items CSV details
     * @return Object
     */
    public function getCSVExtended($selectedProducts)
    {
        $csv = '';
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit(5);
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();

        $data = [];
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem()) {
                $data[] = '"' . $column->getExportHeader() . '"';
            }
        }
        $csv .= implode(',', $data) . "\n";
                
        foreach ($this->getCollection() as $item) {
            if (!in_array($item->getData('entity_id'), $selectedProducts)) {
                continue;
            }
            $data = [];
            foreach ($this->getColumns() as $column) {
                if (!$column->getIsSystem()) {
                    // var_dump(expression)
                    if ($column->getExportHeader() == 'Image') {
                        $data[] = '"' . str_replace(
                            ['"', '\\'],
                            ['""', '\\\\'],
                            $item->getData('image')
                        ) . '"';
                    } elseif ($column->getExportHeader() == 'Warehouse Qty') {
                        $data[] = '"' . str_replace(
                            ['"', '\\'],
                            ['""', '\\\\'],
                            $this->getWarehouseqty($item->getId())
                        ) . '"';
                    } else {
                        $data[] = '"' . str_replace(
                            ['"', '\\'],
                            ['""', '\\\\'],
                            $column->getRowFieldExport($item)
                        ) . '"';
                    }
                }
            }
            $csv .= implode(',', $data) . "\n";
        }
        // exit;
        if ($this->getCountTotals()) {
            $data = [];
            foreach ($this->getColumns() as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = '"' . str_replace(
                        ['"', '\\'],
                        ['""', '\\\\'],
                        $column->getRowFieldExport($this->getTotals())
                    ) . '"';
                }
            }
            $csv .= implode(',', $data) . "\n";
        }

        return $csv;
    }

    /**
     * Get pending items warehouse qty
     * @param  int $rowId
     * @return String
     */
    public function getWarehouseqty($rowId)
    {

        $txtbox = '';
        $this->_resources = $this->_resourceConnection;
        $connection = $this->_resources->getConnection();
        $tableName = $this->_resources->getTableName('bc_warehouse_product_is');
        $getIncrIds = $connection->select()
                ->from($tableName, array('warehouse_id', 'quantity'))
                ->where('product_id = ' . $rowId);
        $getData = $connection->fetchAll($getIncrIds);
        if (!empty($getData[0])) {
            $warehouseModel = $this->_warehouseModel;
            $backendUrl = $this->_backendUrl;
            
            for ($i = 0; $i < count($getData); $i++) {
                if ($i == count($getData) - 1) {
                    $txtbox .= $warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . ":" . $getData[$i]['quantity'];
                } else {
                    $txtbox .= $warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . ":" . $getData[$i]['quantity'] . ";";
                }
            }
        } else {
            $txtbox .= '';
        }
        return $txtbox;
    }

    /**
     * Pending item row url
     * @param  int $row
     * @return Bool
     */
    public function getRowUrl($row)
    {
        return false;
    }
}
