<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseinvoice;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $moduleManager;
    protected $_setsFactory;
    protected $_productFactory;
    protected $_type;
    protected $_status;
    protected $_collectionFactory;
    protected $_supplier;
    protected $_poInvStatus;
    protected $_visibility;
    protected $_websiteFactory;
    protected $_inventorysystemHelper;
    protected $_messageManager;

    /**
     * @param \Magento\Backend\Block\Template\Context                                 $context
     * @param \Magento\Backend\Helper\Data                                            $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory                                     $websiteFactory
     * @param \Biztech\Inventorysystem\Model\ResourceModel\Purchaseinvoice\Collection $collectionFactory
     * @param \Magento\Framework\Module\Manager                                       $moduleManager
     * @param \Biztech\Inventorysystem\Model\PurchaseOrders\Supplier                  $supplier
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoice\Status                   $poInvStatus
     * @param \Biztech\Inventorysystem\Helper\Data                                    $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface                             $messageManager
     * @param array                                                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Biztech\Inventorysystem\Model\ResourceModel\Purchaseinvoice\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Biztech\Inventorysystem\Model\PurchaseOrders\Supplier $supplier,
        \Biztech\Inventorysystem\Model\Purchaseinvoice\Status $poInvStatus,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    ) {

        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->_supplier = $supplier;
        $this->_poInvStatus = $poInvStatus;
        $this->_inventorysystemHelper = $inventorysystemHelper;
        $this->_messageManager = $messageManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('productGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        try {
            $this->inventorysystemHelper = $this->_inventorysystemHelper;
            $this->messageManager = $this->_messageManager;
            if ($this->inventorysystemHelper->isEnable()) {
                $collection = $this->_collectionFactory->load();
                $this->setCollection($collection);
                parent::_prepareCollection();
                return $this;
            } else {
                $this->messageManager->addError(__('Extension- Magemob Inventory is not enabled. Please enable it from Store > Configuration > Biztech > Magemob Inventory.'));
                return $this;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                    'websites',
                    'catalog_product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left'
                );
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
            'header' => __('ID'),
            'type' => 'number',
            'index' => 'id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
                ]
        );
        $this->addColumn(
            'invoice_incr_id',
            [
            'header' => __('Invoice #'),
            'index' => 'invoice_incr_id',
            'class' => 'invoice_incr_id'
                ]
        );
        $this->addColumn(
            'purchase_order_id',
            [
            'header' => __('Purchase Order #'),
            'index' => 'purchase_order_id',
            'class' => 'purchase_order_id'
                ]
        );
        $this->addColumn(
            'supplier_id',
            [
            'header' => __('Supplier'),
            'index' => 'supplier_id',
            'class' => 'supplier_id',
            'type' => 'options',
            'options' => $this->_supplier->toOptionArray()
                ]
        );
        $store = $this->_getStore();
        $this->addColumn(
            'sub_total',
            [
            'header' => __('Sub Total'),
            'index' => 'sub_total',
            'class' => 'sub_total',
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode()
                ]
        );
        $this->addColumn(
            'ship_cost',
            [
            'header' => __('Shipping Cost'),
            'index' => 'ship_cost',
            'class' => 'ship_cost',
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode()
                ]
        );
        $this->addColumn(
            'grand_total',
            [
            'header' => __('Total'),
            'index' => 'grand_total',
            'class' => 'grand_total',
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode()
                ]
        );
        $this->addColumn(
            'invoice_status',
            [
            'header' => __('Inv. Status'),
            'index' => 'invoice_status',
            'class' => 'invoice_status',
            'type' => 'options',
            'options' => $this->_poInvStatus->toOptionArray()
                ]
        );
        /* {{CedAddGridColumn}} */

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
        
        $statuses = $this->_poInvStatus->toOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('invoice_status', array(
            'label' => __('Change Invoice status'),
            'url' => $this->getUrl('inventorysystem/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'invoice_status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('inventorysystem/*/index', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'inventorysystem/*/view',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}
