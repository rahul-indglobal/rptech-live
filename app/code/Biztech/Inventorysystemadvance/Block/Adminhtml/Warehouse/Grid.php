<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse;

use Biztech\Inventorysystemadvance\Model\ResourceModel\Warehouse\Status;
use Biztech\Inventorysystemadvance\Model\ResourceModel\Warehouse\Primary;

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
    protected $_inventorysystemHelper;
    protected $_messageManager;

    /**
     * @param \Magento\Backend\Block\Template\Context                                  $context
     * @param \Magento\Backend\Helper\Data                                             $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory                                      $websiteFactory
     * @param \Biztech\Inventorysystemadvance\Model\ResourceModel\Warehouse\Collection $collectionFactory
     * @param \Magento\Framework\Module\Manager                                        $moduleManager
     * @param Status                                                                   $supplierStatus
     * @param Primary                                                                  $primaryWarehouse
     * @param \Biztech\Inventorysystem\Helper\Data                                     $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface                              $messageManager
     * @param array                                                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Biztech\Inventorysystemadvance\Model\ResourceModel\Warehouse\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        Status $supplierStatus,
        Primary $primaryWarehouse,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    ) {

        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->_supplierStatus = $supplierStatus;
        $this->_primaryWarehouse = $primaryWarehouse;
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

        $this->setId('warehouseGrid');
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
                $this->messageManager->addError(__('Extension- Inventory Management is not enabled. Please enable it from Store > Configuration > Biztech > Magemob Inventory.'));
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
            //'header_css_class' => 'col-id',
            'column_css_class' => 'col-warehouse_id'
                ]
        );
        $this->addColumn(
            'warehouse_name',
            [
            'header' => __('Warehouse Name'),
            'index' => 'warehouse_name',
            'class' => 'warehouse_name'
                ]
        );
        $this->addColumn(
            'city',
            [
            'header' => __('City'),
            'index' => 'city',
            'class' => 'col-warehouse_city'
                ]
        );
        $this->addColumn(
            'state',
            [
            'header' => __('State'),
            'index' => 'state',
            'class' => 'col-warehouse_state',
            'renderer' => 'Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer\State'
                ]
        );

        $this->addColumn(
            'country',
            [
            'header' => __('Country'),
            'index' => 'country',
            'class' => 'col-warehouse_country',
            'renderer' => 'Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer\Country'
                ]
        );
        $getSupplierStatus = $this->_supplierStatus->toOptionArray();
        $supplierStatus = [];
        for ($i = 0; $i < count($getSupplierStatus); $i++) {
            $supplierStatus[$getSupplierStatus[$i]['value']] = $getSupplierStatus[$i]['label'];
        }
        $this->addColumn(
            'status',
            [
            'header' => __('Status'),
            'index' => 'status',
            'class' => 'col-warehouse_status',
            'type' => 'options',
            'options' => $supplierStatus
                ]
        );

        $getprimaryWarehouse = $this->_primaryWarehouse->toOptionArray();
        $primaryWarehouse = [];
        for ($i = 0; $i < count($getprimaryWarehouse); $i++) {
            $primaryWarehouse[$getprimaryWarehouse[$i]['value']] = $getprimaryWarehouse[$i]['label'];
        }
        $this->addColumn(
            'primary_warehouse',
            [
            'header' => __('Primary Warehouse'),
            'index' => 'primary_warehouse',
            'class' => 'col-warehouse_primary_warehouse',
            'type' => 'options',
            'options' => $primaryWarehouse
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
