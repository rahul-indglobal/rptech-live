<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Productgrid;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class Selectproductgrid extends \Magento\Backend\Block\Widget\Grid\Extended
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
    protected $_inventorysystemHelper;
    protected $_messageManager;

    /**
     * @param \Magento\Backend\Block\Template\Context                                $context
     * @param \Magento\Backend\Helper\Data                                           $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory                                    $websiteFactory
     * @param \Biztech\Inventorysystemadvance\Model\ResourceModel\Barcode\Collection $collectionFactory
     * @param \Magento\Framework\Module\Manager                                      $moduleManager
     * @param \Biztech\Inventorysystemadvance\Model\Barcode\Status                   $status
     * @param \Biztech\Inventorysystem\Model\PurchaseOrders\Supplier                 $supplier
     * @param \Magento\Eav\Model\Config                                              $eavConfig
     * @param Config                                                                 $config
     * @param Collection                                                             $productCollection
     * @param \Biztech\Inventorysystem\Helper\Data                                   $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface                            $messageManager
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
        Config $config,
        Collection $productCollection,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->_status = $status;
        $this->_supplier = $supplier;
        $this->eavConfig = $eavConfig;
        $this->_productConfig = $config;
        $this->_productCollection = $productCollection->load();
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
        $this->setId('barcode_create_product_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
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
                $websites = $this->inventorysystemHelper->getAllWebsites();
                $collection = $this->_productCollection
                        ->addAttributeToSelect($this->_productConfig->getProductAttributes())
                        ->addWebsiteFilter($websites);
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
        $this->addColumn('in_products', [
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_products[]',
            'align' => 'center',
            'index' => 'entity_id',
            'values' => $this->_getSelectedProducts(),
            'sortable' => false,
            'width' => '10px'
        ]);

        $this->addColumn('entity_id', [
            'header' => __('ID'),
            'index' => 'entity_id',
            'width' => '10px'
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'index' => 'name',
            'width' => '500px'
        ]);

        $this->addColumn('sku', [
            'header' => __('SKU'),
            'index' => 'sku',
            'width' => '500px',
            'renderer' => '\Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Renderer\Renderer'
        ]);

        $this->addColumn('barcode', [
            'header' => __('Barcode'),
            'index' => 'barcode',
            'filter' => false,
            'sortable' => false,
            'renderer' => '\Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Renderer\Renderer'
        ]);

        $this->addColumn('qty', [
            'header' => __('Qty'),
            'index' => 'qty',
            'filter' => false,
            'sortable' => false,
            'renderer' => '\Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Renderer\Renderer'
        ]);

        $this->addColumn('supplier', [
            'header' => __('Supplier'),
            'index' => 'supplier',
            'filter' => false,
            'sortable' => false,
            'renderer' => '\Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Renderer\Renderer'
        ]);

        $this->addColumn('purchase_order', [
            'header' => __('Purchase Order'),
            'index' => 'purchase_order',
            'filter' => false,
            'sortable' => false,
            'renderer' => '\Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Renderer\Renderer'
        ]);

        $this->addColumn('barcode_status', [
            'header' => __('Status'),
            'index' => 'barcode_status',
            'filter' => false,
            'sortable' => false,
            'renderer' => '\Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Renderer\Renderer'
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('increment_id');
        $this->getMassactionBlock()->setFormFieldName('purchaseorders');

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('inventorysystemadvance/*/createBarcode', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return false;
    }

    /**
     * @return object
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('products', []);
        return $products;
    }

    /**
     * @return int
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return object
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->addOptionsToResult();
        return parent::_afterLoadCollection();
    }
}
