<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Managesupplier;

use Biztech\Inventorysystem\Model\ResourceModel\Managesupplier\Status;

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
    protected $_supplierStatus;
    protected $_inventorysystemHelper;
    protected $_messageManager;

    /**
     * @param \Magento\Backend\Block\Template\Context                                $context
     * @param \Magento\Backend\Helper\Data                                           $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory                                    $websiteFactory
     * @param \Biztech\Inventorysystem\Model\ResourceModel\Managesupplier\Collection $collectionFactory
     * @param \Magento\Framework\Module\Manager                                      $moduleManager
     * @param Status                                                                 $supplierStatus
     * @param \Biztech\Inventorysystem\Helper\Data                                   $inventorysystemHelper
     * @param \Magento\Framework\Message\ManagerInterface                            $messageManager
     * @param array                                                                  $data
     */
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Biztech\Inventorysystem\Model\ResourceModel\Managesupplier\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        Status $supplierStatus,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    ) {

        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->_supplierStatus = $supplierStatus;
        $this->_inventorysystemHelper = $inventorysystemHelper;
        $this->_messageManager = $messageManager;
        parent::__construct($context, $backendHelper, $data);
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
            'inventorysystem/*/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('productGrid');
        $this->setDefaultSort('supplier_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
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
            'supplier_id',
            [
                'header' => __('ID'),
                'index' => 'supplier_id',
                'class' => 'supplier_id',
                'type' => 'number',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'first_name',
            [
                'header' => __('First Name'),
                'index' => 'first_name',
                'class' => 'first_name'
            ]
        );
        $this->addColumn(
            'last_name',
            [
                'header' => __('Last Name'),
                'index' => 'last_name',
                'class' => 'last_name'
            ]
        );
        $this->addColumn(
            'company',
            [
                'header' => __('Company'),
                'index' => 'company',
                'class' => 'company'
            ]
        );
        $this->addColumn(
            'contact_person',
            [
                'header' => __('Contact Person'),
                'index' => 'contact_person',
                'class' => 'contact_person'
            ]
        );
        $this->addColumn(
            'email',
            [
                'header' => __('Email'),
                'index' => 'email',
                'class' => 'email'
            ]
        );

        $getSupplierStatus = $this->_supplierStatus->toOptionArray();
        $supplierStatus = [];
        for ($i = 0; $i < count($getSupplierStatus); $i++) {
            $supplierStatus[$getSupplierStatus[$i]['value']] = $getSupplierStatus[$i]['label'];
        }
        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'class' => 'is_active',
                'type' => 'options',
                'options' => $supplierStatus
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

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => __('Delete'),
                'url' => $this->getUrl('inventorysystem/*/massDelete'),
                'confirm' => __('Are you sure you want to delete?')
            )
        );
        return $this;
    }
}
