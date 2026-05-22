<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $moduleManager;
    protected $_setsFactory;
    protected $_productFactory;
    protected $_type;
    protected $_status;
    protected $_collectionFactory;
    protected $_visibility;
    protected $_websiteFactory;
    protected $_supplier;
    protected $eavConfig;
    protected $_resource;
    protected $_inventorysystemHelper;
    protected $_messageManager;
    protected $_resourceConnection;

    /**
     * @param \Magento\Backend\Block\Template\Context                                $context
     * @param \Magento\Backend\Helper\Data                                           $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory                                    $websiteFactory
     * @param \Biztech\Inventorysystemadvance\Model\ResourceModel\Barcode\Collection $collectionFactory
     * @param \Magento\Framework\Module\Manager                                      $moduleManager
     * @param \Biztech\Inventorysystemadvance\Model\Barcode\Status                   $status
     * @param \Biztech\Inventorysystem\Model\PurchaseOrders\Supplier                 $supplier
     * @param \Magento\Eav\Model\Config                                              $eavConfig
     * @param \Magento\Framework\App\ResourceConnection                              $resource
     * @param \Biztech\Inventorysystem\Helper\Data                                   $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface                            $messageManager
     * @param \Magento\Framework\App\ResourceConnection                              $resourceConnection
     * @param array                                                                  $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Biztech\Inventorysystemadvance\Model\ResourceModel\Barcode\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Biztech\Inventorysystemadvance\Model\Barcode\Status $status,
        \Biztech\Inventorysystem\Model\PurchaseOrders\Supplier $supplier,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        array $data = []
    ) {

        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->_status = $status;
        $this->_supplier = $supplier;
        $this->eavConfig = $eavConfig;
        $this->_resource = $resource;
        $this->_inventorysystemHelper = $inventorysystemHelper;
        $this->_messageManager = $messageManager;
        $this->_resourceConnection = $resourceConnection;
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

                $this->_resources = $this->_resourceConnection;
                $connection = $this->_resources->getConnection();

                $collection->join(
                    ['cp' => $this->_resource->getTableName('catalog_product_entity')],
                    'main_table.product_id=cp.entity_id',
                    ['sku']
                );

                $attribIdName = $this->eavConfig->getAttribute('catalog_product', 'name')->getId();
                //$attribIdName = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'name');
                $collection->getSelect()->joinLeft(
                    ['cpv' => $this->_resource->getTableName('catalog_product_entity_varchar')],
                    'main_table.product_id=cpv.entity_id and cpv.attribute_id=' . $attribIdName,
                    ['value']
                );

                $collection->getSelect()->joinLeft(
                    ['sp' => $this->_resource->getTableName('bc_supplier_is')],
                    'main_table.supplier=sp.supplier_id',
                    ['concat( first_name, " ", last_name ) AS sup_name']
                );

                $collection->getSelect()->joinLeft(
                    ['po' => $this->_resource->getTableName('bc_purchaseorders_is')],
                    'main_table.purchaseorder_id=po.id',
                    ['purchase_order_id']
                );
                $collection->getSelect()->group('main_table.id');
                $collection->setOrder('main_table.id', 'DESC');

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
            'column_css_class' => 'col-id',
            'filter_index' => 'main_table.id',
            'width' => '10px'
                ]
        );
        $this->addColumn(
            'barcode',
            [
            'header' => __('Barcode'),
            'index' => 'barcode',
            'class' => 'barcode',
            'filter_index' => 'main_table.barcode',
            'width' => '100px',
                ]
        );
        $this->addColumn(
            'created_at',
            [
            'header' => __('Generate Date'),
            'index' => 'created_at',
            'class' => 'created_at',
            'filter_index' => 'main_table.created_at',
            'type' => 'date',
            'width' => '100px'
                ]
        );
        $this->addColumn(
            'value',
            [
            'header' => __('Product Name'),
            'index' => 'value',
            'class' => 'value',
            'filter_index' => 'cpv.value',
            'width' => '100px'
                ]
        );
        $this->addColumn(
            'sku',
            [
            'header' => __('SKU'),
            'index' => 'sku',
            'class' => 'sku',
            'filter_index' => 'cp.sku',
            'width' => '100px'
                ]
        );
        $this->addColumn(
            'barcode_qty',
            [
            'header' => __('Qty'),
            'index' => 'barcode_qty',
            'class' => 'barcode_qty',
            'filter_index' => 'main_table.barcode_qty',
            'width' => '50px'
                ]
        );
        $this->addColumn(
            'sup_name',
            [
            'header' => __('Supplier'),
            'index' => 'sup_name',
            'filter_index' => 'sp.supplier_id',
            'class' => 'sup_name',
            'width' => '100px',
            'type' => 'options',
            'options' => $this->_supplier->toOptionArray(),
                ]
        );
        $this->addColumn(
            'purchase_order_id',
            [
            'header' => __('Purchase Order'),
            'index' => 'purchase_order_id',
            'class' => 'purchase_order_id',
            'filter_index' => 'po.purchase_order_id',
            'width' => '50px'
                ]
        );
        $this->addColumn(
            'status',
            [
            'header' => __('Status'),
            'index' => 'status',
            'class' => 'status',
            'filter_index' => 'main_table.status',
            'width' => '50px',
            'type' => 'options',
            'options' => $this->_status->toOptionArray(),
                ]
        );

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
        $this->getMassactionBlock()->setFormFieldName('barcodes');

        $statuses = $this->_status->toOptionArray();
        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'delete',
            [
            'label' => __('Change status'),
            'url' => $this->getUrl('*/*/massStatus'),
            'additional' => [
                'visibility' => [
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Status'),
                    'values' => $statuses
                ]
                ]
                ]
        );
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('inventorysystemadvance/*/index', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'inventorysystemadvance/*/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}
