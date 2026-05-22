<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    public $Request;
    public $InventorysystemHelper;
    public $catalogConfig;
    protected $moduleManager;
    protected $setsFactory;
    protected $productFactory;
    protected $_status;
    protected $_messageManager;
    protected $_stockstatus;
    protected $_resourceConnection;
    protected $_pageConfig;

    /**
     * @param \Magento\Backend\Block\Template\Context                                 $context
     * @param \Magento\Backend\Helper\Data                                            $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory                                     $websiteFactory
     * @param \Biztech\Inventorysystem\Model\ResourceModel\Inventorysystem\Collection $collectionFactory
     * @param \Magento\Framework\Module\Manager                                       $moduleManager
     * @param \Magento\Framework\App\Request\Http                                     $Request
     * @param \Biztech\Inventorysystem\Helper\Data                                    $InventorysystemHelper
     * @param \Magento\Catalog\Model\ProductFactory                                   $productFactory
     * @param \Magento\Catalog\Model\Config                                           $catalogConfig
     * @param CollectionFactory                                                       $setsFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status                  $status
     * @param \Magento\Framework\Message\ManagerInterface                             $messageManager
     * @param \Biztech\Inventorysystem\Model\Stockstatus                              $stockstatus
     * @param \Magento\Framework\App\ResourceConnection                               $resourceConnection
     * @param array                                                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Biztech\Inventorysystem\Model\ResourceModel\Inventorysystem\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\App\Request\Http $Request,
        \Biztech\Inventorysystem\Helper\Data $InventorysystemHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        CollectionFactory $setsFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $status,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Biztech\Inventorysystem\Model\Stockstatus $stockstatus,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        array $data = []
    ) {

        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->Request = $Request;
        $this->InventorysystemHelper = $InventorysystemHelper;
        $this->productFactory = $productFactory;
        $this->catalogConfig = $catalogConfig;
        $this->setsFactory = $setsFactory;
        $this->_status = $status;
        $this->_messageManager = $messageManager;
        $this->_stockstatus = $stockstatus;
        $this->_resourceConnection = $resourceConnection;
        $this->_pageConfig = $context->getPageConfig();
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();

        $this->setId('inventorysystemGrid');
        $this->setDefaultSort('inventorysystem_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $page = $this->_pageConfig;
        $page->addPageAsset('Biztech_Inventorysystem::js/customgrid.js');
    }

    /**
     * Prepare collection
     * @return Object
     */
    protected function _prepareCollection()
    {
        try {
            $collection = $this->_collectionFactory->load();
            $curWarehouse = '';
            if (!$this->Request->getParam('inventorysystem') && $this->moduleManager->isEnabled('Biztech_Inventorysystemadvance')) {
                $curWarehouse = $this->Request->getParam('warehouse');
            }

            $websites = $this->InventorysystemHelper->getAllWebsites();
            $this->_resources = $this->_resourceConnection;
            $connection = $this->_resources->getConnection();
            $collection = $this->productFactory->create()->getCollection()
                    ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
                    ->addWebsiteFilter($websites);
            $collection->joinField('qty', $connection->getTableName('cataloginventory_stock_item'), 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');

            $collection->joinField('is_in_stock', $connection->getTableName('cataloginventory_stock_item'), 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');

            /* Start: Manage Stocks grid , add warehouse filter from global selector */
            if ($curWarehouse) {
                $collection->joinField('product_id', $connection->getTableName('bc_warehouse_product_is'), 'product_id', 'product_id=entity_id', '{{table}}.warehouse_id=' . $curWarehouse);
            }
            /* End: Manage Stocks grid , add warehouse filter from global selector */

            /* pass filter to select products for export in csv */
            if ($this->Request->getParam('inventorysystem')) {
                $collection->addAttributeToFilter(array(array('attribute' => 'entity_id', 'in' => $this->Request->getParam('inventorysystem'))));
            }

            $collection->addAttributeToFilter(array(array('attribute' => 'type_id', 'nin' => array('grouped', 'configurable', 'bundle'))));

            /* Check if extension is enabled with activation key */
            $this->inventorysystemHelper = $this->InventorysystemHelper;
            $this->messageManager = $this->_messageManager;
            if ($this->inventorysystemHelper->isEnable()) {
                //parent::_construct();
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
     * Prepare columns
     * @return Void
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
            'header' => __('ID'),
            'index' => 'entity_id',
            'class' => 'entity_id'
                ]
        );
        $this->addColumn(
            'image',
            [
            'header' => _('Image'),
            'align' => 'left',
            'index' => 'image',
            'width' => 97,
            'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Pendingitems\Renderer\Image',
            'filter' => false,
            'sortable' => false,
                ]
        );

        $this->addColumn(
            'name',
            [
            'header' => __('Name'),
            'index' => 'name',
            'class' => 'filename'
                ]
        );
        $this->addColumn(
            'sku',
            [
            'header' => __('SKU'),
            'index' => 'sku',
            'class' => 'filename'
                ]
        );

        $sets = $this->setsFactory->create()
                ->setEntityTypeFilter($this->productFactory->create()->getResource()->getTypeId())
                ->load()
                ->toOptionHash();

        if (!$this->Request->getParam('inventorysystem')) {
            $this->addColumn('set_name', array(
                'header' => __('Attrib. Set Name'),
                'width' => '50px',
                'index' => 'attribute_set_id',
                'type' => 'options',
                'options' => $sets,
            ));
        }

        $this->addColumn(
            'price',
            [
            'header' => __('Price'),
            //'type' => 'currency',
            'type' => 'price',
            'currency_code' => (string) $this->_scopeConfig->getValue(
                \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            ),
            'index' => 'price',
            'header_css_class' => 'col-price',
            'column_css_class' => 'col-price'
                ]
        );

        $this->addColumn(
            'qty',
            [
            'header' => __('Avail. Qty'),
            'index' => 'qty',
            'class' => 'qty'
                ]
        );
        if ($this->moduleManager->isEnabled('Biztech_Inventorysystemadvance')) {
            $this->addColumn('warehouse_qty', array(
                'header' => __('Warehouse Qty'),
                'index' => 'warehouse_qty',
                'filter' => false,
                'sortable' => false,
                'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer\Warehouseqty'
            ));
        }

        if (!$this->Request->getParam('inventorysystem')) {
            $this->addColumn('total_qty', array(
                'header' => __('Total Qty'),
                'width' => '10px',
                'align' => 'center',
                'type' => 'input',
                'index' => 'total_qty',
                'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer\Totalqty'
            ));
        }

        if (!$this->Request->getParam('inventorysystem')) {
            $this->addColumn('qty_level', array(
                'header' => __('Inc/Dec Qty'),
                'index' => 'qty_level',
                'sortable' => false,
                'filter' => false,
                'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer\Qtylevel'
            ));
        }

        if ($this->Request->getParam('inventorysystem')) {
            $this->addColumn('is_in_stock', array(
                'header' => __('Stock Status'),
                'index' => 'is_in_stock',
                'type' => 'options',
                'options' => array('1' => 'In Stock', '0' => 'Out Of Stock'),
            ));
        }

        if (!$this->Request->getParam('inventorysystem')) {
            $this->addColumn('is_in_stock', array(
                'header' => __('Stock Status'),
                'index' => 'is_in_stock',
                'type' => 'options',
                'options' => array('1' => 'In Stock', '0' => 'Out Of Stock'),
                'sortable' => true,
                'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer\Stockstatus'
            ));

            $this->addColumn('comment', array(
                'header' => __('Comment'),
                'index' => 'comment',
                'width' => '150px',
                'sortable' => false,
                'filter' => false,
                'renderer' => 'Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer\Comment'
            ));
        }

        $this->addColumn(
            'status',
            [
            'header' => __('Status'),
            'index' => 'status',
            'width' => '50px',
            'type' => 'options',
            'options' => $this->_status->getOptionArray(),
            'header_css_class' => 'col-status',
            'column_css_class' => 'col-status'
                ]
        );

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * Prepare mass action
     * @return Void
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('inventorysystem_id');
        $this->getMassactionBlock()->setFormFieldName('inventorysystem');

        /* on select of this massaction it will set the values from js */
        $this->getMassactionBlock()->addItem('updateInv', array(
            'label' => __('Update Inventory'),
            'url' => $this->getUrl('*/*/updateInventory', array('_current' => true)),
            'confirm' => __('Are you sure want to update qty of all selected product/s?'),
            'selected' => true
        ));
        
        $statuses = $this->_stockstatus->getOptionArray();
        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => __('Change Stock status'),
            'url' => $this->getUrl('*/*/updateStockStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Stock Status'),
                    'values' => $statuses
                )
            )
        ));

        $this->getMassactionBlock()->addItem('export_csv', array(
            'label' => __('Export in CSV'),
            'url' => $this->getUrl('*/*/exportSelectedProducts', array('_current' => true))
        ));
        return $this;
    }

    /**
     * Get row url
     * @param  [int] $row
     * @return void
     */
    public function getRowUrl($row)
    {
        return false;
    }
}
