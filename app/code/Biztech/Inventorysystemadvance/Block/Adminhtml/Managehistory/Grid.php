<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Managehistory;

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
    protected $_systemAction;
    protected $_actionType;
    protected $_interface;
    protected $_inventorysystemHelper;
    protected $_messageManager;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Biztech\Inventorysystemadvance\Model\ResourceModel\Managehistory\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Biztech\Inventorysystemadvance\Model\Managehistory\Systemaction $systemAction,
        \Biztech\Inventorysystemadvance\Model\Managehistory\Actiontype $actionType,
        \Biztech\Inventorysystemadvance\Model\Managehistory\SystemInterface $interface,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    ) {

        $this->_collectionFactory = $collectionFactory;
        $this->_actionType = $actionType;
        $this->_systemAction = $systemAction;
        $this->_interface = $interface;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
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
                $collection = $this->_collectionFactory->load()->setOrder('managehistory_id', 'DESC');
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
            'managehistory_id',
            [
            'header' => __('ID'),
            'index' => 'managehistory_id',
            'class' => 'managehistory_id'
                ]
        );
        $this->addColumn(
            'sku',
            [
            'header' => __('SKU'),
            'index' => 'sku',
            'class' => 'sku',
            'width' => '100px'
                ]
        );
        $this->addColumn(
            'system_action',
            [
            'header' => __('System Action'),
            'index' => 'system_action',
            'class' => 'system_action',
            'type' => 'options',
            'options' => $this->_systemAction->toOptionArray()
                ]
        );
        $this->addColumn(
            'action_type',
            [
            'header' => __('Action Type'),
            'index' => 'action_type',
            'class' => 'action_type',
            'type' => 'options',
            'options' => $this->_actionType->toOptionArray()
                ]
        );
        $this->addColumn(
            'qty_before',
            [
            'header' => __('Quantity Before'),
            'index' => 'qty_before',
            'class' => 'qty_before'
                ]
        );
        $this->addColumn(
            'qty_processed',
            [
            'header' => __('Quantity Processed'),
            'index' => 'qty_processed',
            'class' => 'qty_processed'
                ]
        );
        $this->addColumn(
            'final_qty',
            [
            'header' => __('Final Quantity'),
            'index' => 'final_qty',
            'class' => 'final_qty'
                ]
        );
        $this->addColumn(
            'warhouse_qty_before',
            [
            'header' => __('War. Qty Before'),
            'index' => 'warhouse_qty_before',
            'class' => 'warhouse_qty_before'
                ]
        );
        $this->addColumn(
            'warhouse_qty_after',
            [
            'header' => __('War. Qty After'),
            'index' => 'warhouse_qty_after',
            'class' => 'warhouse_qty_after'
                ]
        );
        $this->addColumn(
            'order',
            [
            'header' => __('Additional Info.'),
            'index' => 'order',
            'class' => 'order',
            'renderer' => 'Biztech\Inventorysystemadvance\Block\Adminhtml\Managehistory\Renderer\Orderlink'
                ]
        );
        $this->addColumn(
            'update_date',
            [
            'header' => __('Processed Date'),
            'index' => 'update_date',
            'class' => 'datetime',
            'type' => 'datetime'
                ]
        );
        $this->addColumn(
            'interface',
            [
            'header' => __('Interface'),
            'index' => 'interface',
            'class' => 'interface',
            'type' => 'options',
            'options' => $this->_interface->toOptionArray()
                ]
        );
        $this->addColumn(
            'user',
            [
            'header' => __('User'),
            'index' => 'user',
            'class' => 'user'
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
            'url' => $this->getUrl('inventorysystemadvance/*/massDelete'),
            'confirm' => __('Are you sure?')
                )
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
        return false;
    }
}
