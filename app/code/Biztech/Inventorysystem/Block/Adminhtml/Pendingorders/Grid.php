<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Pendingorders;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Backend\Model\Session\Quote;
use Magento\Sales\Model\Order\Config;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class Grid extends Extended
{

    protected $_orderCollection;
    protected $_sessionQuote;
    protected $_orderConfig;
    protected $_resource;
    protected $_inventorysystemHelper;
    protected $_messageManager;
    
    /**
     * @param Context                                     $context
     * @param Data                                        $backendHelper
     * @param OrderCollectionFactory                      $orderCollection
     * @param Quote                                       $sessionQuote
     * @param Config                                      $orderConfig
     * @param \Magento\Framework\App\ResourceConnection   $resource
     * @param \Biztech\Inventorysystem\Helper\Data        $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param array                                       $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        OrderCollectionFactory $orderCollection,
        Quote $sessionQuote,
        Config $orderConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
        $this->_orderCollection = $orderCollection;
        $this->_sessionQuote = $sessionQuote;
        $this->_orderConfig = $orderConfig;
        $this->_resource = $resource;
        $this->_inventorysystemHelper = $inventorysystemHelper;
        $this->_messageManager = $messageManager;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('pendingordersGrid');
        $this->setDefaultSort('created_at');
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

        $collection = $this->_orderCollection->create();
        $collection->addFilterToMap('bc_backordered', 'main_table.bc_backordered');
        $collection->addFilterToMap('store_id', 'main_table.store_id');
        $collection->addFilterToMap('status', 'main_table.status');
        $collection->addFieldToFilter('bc_backordered', 1);
        $collection->addFieldToFilter('status', ['nin' => ['in' => 'complete']]);
        $tableName = $this->_resource->getTableName('bc_purchaseorders_is');

        $collection->getSelect()->joinLeft(
            ['purchaseorders' => $tableName],
            'purchaseorders.sales_order_id = main_table.increment_id',
            ['po_status' => 'purchaseorders.status', 'purchaseorders.purchase_order_id']
        )->group('increment_id');

        /* Check if extension is enabled with activation key */

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
        $this->addColumn('increment_id', [
            'header' => __('Order #'),
            // 'type' => 'number',
            'index' => 'increment_id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);

        $this->addColumn('created_at', [
            'header' => __('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '200px',
            'sortable' => false
        ]);


        $this->addColumn('grand_total', [
            'header' => __('Price'),
            'column_css_class' => 'price',
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => $this->getStore()->getCurrentCurrencyCode(),
            'rate' => $this->getStore()->getBaseCurrency()->getRate($this->getStore()->getCurrentCurrencyCode()),
            'index' => 'grand_total',
            'renderer' => 'Magento\Sales\Block\Adminhtml\Order\Create\Search\Grid\Renderer\Price',
            'sortable' => false
        ]);

        $this->addColumn('status', [
            'header' => 'Status',
            'index' => 'status',
            'type' => 'options',
            'width' => '200px',
            'sortable' => true,
            'options' => $this->_orderConfig->getStatuses()
        ]);

        $this->addColumn('po_id_status', [
            'header' => __('PO Order Id and Status'),
            'index' => 'po_id_status',
            'width' => '100px',
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Pendingorders\Renderer\Poidstatus'
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->_sessionQuote->getStore();
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
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('increment_id');
        $this->getMassactionBlock()->setFormFieldName('pendingorders');

        $this->getMassactionBlock()->addItem('generatePOFromItems', array(
            'label' => __('Generate PO'),
            'url' => $this->getUrl('*/purchaseorders/purchaseorderform', array('_current' => true)),
            'selected' => true
        ));


        return $this;
    }

    /**
     * Pending orders row url
     * @param  int $row
     * @return Bool
     */
    public function getRowUrl($row)
    {
        return false;
    }
}
