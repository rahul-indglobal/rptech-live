<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Biztech\Inventorysystem\Model\ResourceModel\Purchaseorders\Collection as PurchaseOrderCollection;
use Biztech\Inventorysystem\Model\PurchaseOrders\Status;
use Biztech\Inventorysystem\Model\PurchaseOrders\Supplier;

class Grid extends Extended
{

    protected $_purchaseorderCollection;
    protected $_poStatus;
    protected $_supplier;
    protected $_inventorysystemHelper;
    protected $_messageManager;

    /**
     * @param Context                                     $context
     * @param Data                                        $backendHelper
     * @param PurchaseOrderCollection                     $purchaseorderCollection
     * @param Status                                      $poStatus
     * @param Supplier                                    $supplier
     * @param \Biztech\Inventorysystem\Helper\Data        $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param array                                       $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        PurchaseOrderCollection $purchaseorderCollection,
        Status $poStatus,
        Supplier $supplier,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
        $this->_purchaseorderCollection = $purchaseorderCollection;
        $this->_poStatus = $poStatus;
        $this->_supplier = $supplier;
        $this->_inventorysystemHelper = $inventorysystemHelper;
        $this->_messageManager = $messageManager;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('purchaseorderGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * Prepare collection
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_purchaseorderCollection->load();
        
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
     * Prepare columns
     * @return Void
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'id',
            [
            'header' => __('ID'),
            'index' => 'id',
            'class' => 'id',
            'type' => 'number',
            'width' => '10px',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
                ]
        );
        $this->addColumn(
            'purchase_order_id',
            [
            'header' => __('Purchase Order #'),
            'index' => 'purchase_order_id',
            'class' => 'purchase_order_id',
            'width' => '100px',
            'header_css_class' => 'col-purchase_order_id',
            'column_css_class' => 'col-purchase_order_id'
                ]
        );
        $this->addColumn(
            'created_at',
            [
            'header' => __('PO Order Date'),
            'index' => 'created_at',
            'class' => 'created_at',
            'width' => '100px',
            'type' => 'datetime',
            'width' => '100px'
                ]
        );
        $this->addColumn(
            'supplier_id',
            [
            'header' => __('Supplier'),
            'index' => 'supplier_id',
            'class' => 'supplier_id',
            'width' => '100px',
            'type' => 'options',
            'options' => $this->_supplier->toOptionArray()
                ]
        );
        $this->addColumn(
            'sales_order_id',
            [
            'header' => __('Sales Order #'),
            'index' => 'sales_order_id',
            'class' => 'sales_order_id',
            'width' => '100px',
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer\Salesorderid'
                ]
        );
        $store = $this->_getStore();

        $this->addColumn(
            'total',
            [
            'header' => __('Total'),
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'height' => '100%',
            'index' => 'total',
            'class' => 'total',
            'header_css_class' => 'col-price',
            'column_css_class' => 'col-price'
                ]
        );

        $this->addColumn(
            'status',
            [
            'header' => __('PO Order Status'),
            'index' => 'status',
            'class' => 'status',
            'type' => 'options',
            'options' => $this->_poStatus->toOptionArray()
                ]
        );

        $this->addColumn(
            'invoiced',
            [
            'header' => __('Invoice #'),
            'index' => 'invoiced',
            'width' => '100px',
            'class' => 'invoiced',
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer\Invoice'
                ]
        );

        $this->addColumn(
            'action',
            [
            'header' => __('Action'),
            'index' => 'action',
            'class' => 'action',
            'filter' => false,
            'type' => 'action',
            'getter' => 'getId',
            'sortable' => false,
            'is_system' => true,
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer\Action'
                ]
        );

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
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
     * Prepare mass action
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('increment_id');
        $this->getMassactionBlock()->setFormFieldName('purchaseorders');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => __('Delete'),
            'url' => $this->getUrl('*/*/massDelete', array('_current' => true)),
            'selected' => true,
            'confirm' => __('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('status', array(
            'label' => __('Change Status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => [
                'visibility' => [
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Status'),
                    'values' => $this->_poStatus->toOptionArray()
                ]
            ]
        ));


        return $this;
    }
    /**
     * PO row url
     * @param  int $row
     * @return Bool
     */
    public function getRowUrl($row)
    {
        // return $this->getUrl('*/*/view', ['id' => $row->getId()]);
        return '';
    }
}
