<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Stockreceived;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Biztech\Inventorysystem\Model\StockreceiveditemsFactory;
use Biztech\Inventorysystem\Model\PurchaseOrders\Supplier;
use Biztech\Inventorysystem\Helper\Data as BizHelper;

class Grid extends Extended
{

    protected $_stockreceiveditemsFactory;
    protected $_supplierOptions;
    protected $_bizHelper;
    protected $_poStatus;
    protected $_inventorysystemHelper;
    protected $_messageManager;
    protected $_warehouseModel;
    
    /**
     * @param Context                                              $context
     * @param Data                                                 $backendHelper
     * @param StockreceiveditemsFactory                            $stockreceiveditemsFactory
     * @param Supplier                                             $supplierOptions
     * @param BizHelper                                            $bizHelper
     * @param \Biztech\Inventorysystem\Model\PurchaseOrders\Status $poStatus
     * @param \Biztech\Inventorysystem\Helper\Data                 $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface          $messageManager
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse      $warehouseModel
     * @param array                                                $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        StockreceiveditemsFactory $stockreceiveditemsFactory,
        Supplier $supplierOptions,
        BizHelper $bizHelper,
        \Biztech\Inventorysystem\Model\PurchaseOrders\Status $poStatus,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
        $this->_stockreceiveditemsFactory = $stockreceiveditemsFactory;
        $this->_supplierOptions = $supplierOptions;
        $this->_bizHelper = $bizHelper;
        $this->_poStatus = $poStatus;
        $this->_inventorysystemHelper = $inventorysystemHelper;
        $this->_messageManager = $messageManager;
        $this->_warehouseModel = $warehouseModel;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('stockreceivedGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        
        $this->inventorysystemHelper = $this->_inventorysystemHelper;
        $this->messageManager = $this->_messageManager;
        if ($this->inventorysystemHelper->isEnable()) {
            $_stockreceiveditems = $this->_stockreceiveditemsFactory->create();
            $collection = $_stockreceiveditems->getCollection()->addFieldToFilter('qty_received', ['neq' => 0]);

            $collection->join(
                ['sr' => 'bc_stockreceived_is'],
                'main_table.stockreceived_id=sr.id',
                ['stockreceived_id', 'purchaseorder_id', 'supplier_id', 'created_at', 'status', 'sub_total', 'shipping_cost', 'total', 'received_by']
            );

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
        $this->addColumn('id', [
            'header' => __('ID'),
            // 'type' => 'number',
            'filter_index' => 'main_table.id',
            'index' => 'id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);

        $this->addColumn('stockreceived_id', [
            'header' => __('Stock Received'),
            // 'type' => 'number',
            'filter_index' => 'sr.stockreceived_id',
            'index' => 'stockreceived_id',
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Stockreceived\Renderer\Stockreceivedviewlink',
        ]);

        $this->addColumn('purchaseorder_id', [
            'header' => __('Purchase Order'),
            // 'type' => 'number',
            'filter_index' => 'sr.purchaseorder_id',
            'index' => 'purchaseorder_id',
            'header_css_class' => 'col-purchaseorder-id',
            'column_css_class' => 'col-purchaseorder-id',
        ]);

        $this->addColumn('supplier_id', [
            'header' => __('Supplier'),
            'filter_index' => 'sr.supplier_id',
            'index' => 'supplier_id',
            'type' => 'options',
            'options' => $this->_supplierOptions->toOptionArray(),
            'header_css_class' => 'col-supplier',
            'column_css_class' => 'col-supplier',
        ]);

        $this->addColumn('product_name', [
            'header' => __('Product Name'),
            'index' => 'product_name',
            'width' => '500px',
            'header_css_class' => 'col-product-name',
            'column_css_class' => 'col-product-name',
            'filter_index' => 'main_table.product_name'
        ]);


        $this->addColumn('product_sku', [
            'header' => __('SKU'),
            'index' => 'product_sku',
            'width' => '500px',
            'header_css_class' => 'col-product-sku',
            'column_css_class' => 'col-product-sku',
            'filter_index' => 'main_table.product_sku'
        ]);

        if ($this->_bizHelper->isModuleEnabled('Biztech_Inventorysystemadvance')) {
            $wareHouse = $this->_warehouseModel->getAllOption();
            $this->addColumn('warehouse_id', [
                'header' => __('Warehouse'),
                'index' => 'warehouse_id',
                'type' => 'options',
                'options' => $wareHouse,
                'header_css_class' => 'col-warehouse',
                'column_css_class' => 'col-warehouse',
                'renderer' => '\Biztech\Inventorysystem\Block\Adminhtml\Stockreceived\Renderer\Warehouse',
                'filter_index' => 'main_table.warehouse_id'
            ]);
        }

        $this->addColumn('qty_purchased', [
            'header' => __('Qty. Pur.'),
            'index' => 'qty_purchased',
            'header_css_class' => 'col-qty-pur',
            'column_css_class' => 'col-qty-pur',
            'filter_index' => 'main_table.qty_purchased'
        ]);

        $this->addColumn('qty_received', [
            'header' => __('Qty. Rec.'),
            'index' => 'qty_received',
            'filter_index' => 'main_table.qty_received'
        ]);

        $this->addColumn('cost', [
            'header' => __('Unit Cost'),
            'index' => 'cost',
            'column_css_class' => 'price',
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => $this->getStore()->getCurrentCurrencyCode(),
            'rate' => $this->getStore()->getBaseCurrency()->getRate($this->getStore()->getCurrentCurrencyCode()),
            'filter_index' => 'main_table.cost'
        ]);

        $this->addColumn('sub_total', [
            'header' => __('Sub Total'),
            'index' => 'sub_total',
            'column_css_class' => 'price',
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => $this->getStore()->getCurrentCurrencyCode(),
            'rate' => $this->getStore()->getBaseCurrency()->getRate($this->getStore()->getCurrentCurrencyCode()),
            'filter_index' => 'sr.sub_total'
        ]);

        $this->addColumn('shipping_cost', [
            'header' => __('Shipping Cost'),
            'index' => 'sub_total',
            'column_css_class' => 'price',
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => $this->getStore()->getCurrentCurrencyCode(),
            'rate' => $this->getStore()->getBaseCurrency()->getRate($this->getStore()->getCurrentCurrencyCode()),
            'filter_index' => 'sr.shipping_cost'
        ]);

        $this->addColumn('total', [
            'header' => __('Total'),
            'index' => 'total',
            'column_css_class' => 'price',
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => $this->getStore()->getCurrentCurrencyCode(),
            'rate' => $this->getStore()->getBaseCurrency()->getRate($this->getStore()->getCurrentCurrencyCode()),
            'filter_index' => 'sr.total'
        ]);


        $this->addColumn('received_by', [
            'header' => __('Rec. By'),
            'index' => 'received_by',
            'filter_index' => 'sr.received_by'
        ]);

        $this->addColumn('created_at', [
            'header' => __('Stock Received Date'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '200px',
            'filter_index' => 'sr.created_at',
            'sortable' => false
        ]);

        $this->addColumn('status', [
            'header' => __('PO Order Status'),
            'index' => 'status',
            'type' => 'options',
            'width' => '200px',
            'filter_index' => 'sr.status',
            'sortable' => true,
            'options' => $this->_poStatus->toOptionArray()
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->_getStore();
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
     * Stockreceived row url
     * @param  int $row
     * @return Bool
     */
    public function getRowUrl($row)
    {
        return false;
    }
}
