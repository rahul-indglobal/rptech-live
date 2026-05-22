<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Sales\Order\View\Items\Renderer;

class DefaultRenderer extends \Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer
{
    protected $_messageHelper;
    protected $_checkoutHelper;
    protected $_giftMessage = [];
    protected $scopeConfig;
    protected $_resourceConnection;
    protected $_warehouseModel;
    protected $_orderModel;

    /**
     * @param \Magento\Backend\Block\Template\Context                   $context
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface      $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Framework\Registry                               $registry
     * @param \Magento\GiftMessage\Helper\Message                       $messageHelper
     * @param \Magento\Checkout\Helper\Data                             $checkoutHelper
     * @param \Magento\Framework\App\ResourceConnection                 $resourceConnection
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse           $warehouseModel
     * @param \Magento\Sales\Model\Order                                $orderModel
     * @param array                                                     $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\GiftMessage\Helper\Message $messageHelper,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        \Magento\Sales\Model\Order $orderModel,
        array $data = []
    ) {
        $this->_checkoutHelper = $checkoutHelper;
        $this->_messageHelper = $messageHelper;
        $this->scopeConfig = $context->getScopeConfig();
        $this->_resourceConnection = $resourceConnection;
        $this->_warehouseModel = $warehouseModel;
        $this->_orderModel = $orderModel;
        parent::__construct($context, $stockRegistry, $stockConfiguration, $registry, $messageHelper, $checkoutHelper, $data);
    }

    /**
     * Get order item
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->_getData('item');
    }

    /**
     * Retrieve real html id for field
     *
     * @param string $id
     * @return string
     */
    public function getFieldId($id)
    {
        return $this->getFieldIdPrefix() . $id;
    }

    /**
     * Retrieve field html id prefix
     *
     * @return string
     */
    public function getFieldIdPrefix()
    {
        return 'order_item_' . $this->getItem()->getId() . '_';
    }

    /**
     * Indicate that block can display container
     *
     * @return bool
     */
    public function canDisplayContainer()
    {
        return $this->getRequest()->getParam('reload') != 1;
    }

    /**
     * Retrieve default value for giftmessage sender
     *
     * @return string
     */
    public function getDefaultSender()
    {
        if (!$this->getItem()) {
            return '';
        }

        if ($this->getItem()->getOrder()) {
            return $this->getItem()->getOrder()->getBillingAddress()->getName();
        }

        return $this->getItem()->getBillingAddress()->getName();
    }

    /**
     * Retrieve default value for giftmessage recipient
     *
     * @return string
     */
    public function getDefaultRecipient()
    {
        if (!$this->getItem()) {
            return '';
        }

        if ($this->getItem()->getOrder()) {
            if ($this->getItem()->getOrder()->getShippingAddress()) {
                return $this->getItem()->getOrder()->getShippingAddress()->getName();
            } elseif ($this->getItem()->getOrder()->getBillingAddress()) {
                return $this->getItem()->getOrder()->getBillingAddress()->getName();
            }
        }

        if ($this->getItem()->getShippingAddress()) {
            return $this->getItem()->getShippingAddress()->getName();
        } elseif ($this->getItem()->getBillingAddress()) {
            return $this->getItem()->getBillingAddress()->getName();
        }

        return '';
    }

    /**
     * Retrieve real name for field
     *
     * @param string $name
     * @return string
     */
    public function getFieldName($name)
    {
        return 'giftmessage[' . $this->getItem()->getId() . '][' . $name . ']';
    }

    /**
     * Initialize gift message for entity
     *
     * @return $this
     */
    protected function _initMessage()
    {
        $this->_giftMessage[$this->getItem()->getGiftMessageId()] = $this->_messageHelper->getGiftMessage(
            $this->getItem()->getGiftMessageId()
        );

        // init default values for giftmessage form
        if (!$this->getMessage()->getSender()) {
            $this->getMessage()->setSender($this->getDefaultSender());
        }
        if (!$this->getMessage()->getRecipient()) {
            $this->getMessage()->setRecipient($this->getDefaultRecipient());
        }

        return $this;
    }

    /**
     * Retrieve gift message for entity
     *
     * @return \Magento\GiftMessage\Model\Message
     */
    public function getMessage()
    {
        if (!isset($this->_giftMessage[$this->getItem()->getGiftMessageId()])) {
            $this->_initMessage();
        }

        return $this->_giftMessage[$this->getItem()->getGiftMessageId()];
    }

    /**
     * Retrieve save url
     *
     * @return array
     */
    public function getSaveUrl()
    {
        return $this->getUrl(
            'sales/order_view_giftmessage/save',
            ['entity' => $this->getItem()->getId(), 'type' => 'order_item', 'reload' => true]
        );
    }

    /**
     * Retrieve block html id
     *
     * @return string
     */
    public function getHtmlId()
    {
        return substr($this->getFieldIdPrefix(), 0, -1);
    }

    /**
     * Indicates that block can display giftmessages form
     *
     * @return bool
     */
    public function canDisplayGiftmessage()
    {
        return $this->_messageHelper->isMessagesAllowed(
            'order_item',
            $this->getItem(),
            $this->getItem()->getOrder()->getStoreId()
        );
    }

    /**
     * Display susbtotal price including tax
     *
     * @param Item $item
     * @return string
     */
    public function displaySubtotalInclTax($item)
    {
        return $this->displayPrices(
            $this->_checkoutHelper->getBaseSubtotalInclTax($item),
            $this->_checkoutHelper->getSubtotalInclTax($item)
        );
    }

    /**
     * Display item price including tax
     *
     * @param Item|\Magento\Framework\DataObject $item
     * @return string
     */
    public function displayPriceInclTax(\Magento\Framework\DataObject $item)
    {
        return $this->displayPrices(
            $this->_checkoutHelper->getBasePriceInclTax($item),
            $this->_checkoutHelper->getPriceInclTax($item)
        );
    }

    /**
     * @param \Magento\Framework\DataObject|Item $item
     * @param string $column
     * @param null $field
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getColumnHtml(\Magento\Framework\DataObject $item, $column, $field = null)
    {
        $html = '';
        switch ($column) {
            case 'product':
                if ($this->canDisplayContainer()) {
                    $html .= '<div id="' . $this->getHtmlId() . '">';
                }
                $html .= $this->getColumnHtml($item, 'name');
                if ($this->canDisplayContainer()) {
                    $html .= '</div>';
                }
                break;
            case 'status':
                $html = $item->getStatus();
                break;
            case 'price-original':
                $html = $this->displayPriceAttribute('original_price');
                break;
            case 'tax-amount':
                $html = $this->displayPriceAttribute('tax_amount');
                break;
            case 'tax-percent':
                $html = $this->displayTaxPercent($item);
                break;
            case 'discont':
                $html = $this->displayPriceAttribute('discount_amount');
                break;
            default:
                $html = parent::getColumnHtml($item, $column, $field);
        }
        return $html;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        $columns = array_key_exists('columns', $this->_data) ? $this->_data['columns'] : [];
        return $columns;
    }

    /**
     * Warehouse products list
     * @param  int $productID
     * @param  int $warehouseID
     * @param  int $orderID
     * @param  int $itemID
     * @param  int $qty
     * @return Object
     */
    public function getProductWarehouse($productID, $warehouseID, $orderID, $itemID, $qty)
    {
        
        $this->WarehouseFactory = $this->_warehouseModel;
        $order = $this->_orderModel->load($orderID);
        $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->_resources = $this->_resourceConnection;
        $connection = $this->_resources->getConnection();
        $tableName = $this->_resources->getTableName('bc_warehouse_product_is');
        $getIncrIds = $connection->select()
                ->from($tableName, array('warehouse_id'))
                ->where('product_id = ' . $productID);
        $getData = $connection->fetchAll($getIncrIds);
        if ($order->canShip()) {
            $selectHtml = "<select style='width:120px' class='required-entry required-entry select warehouse_select' id='gr_warehouse_$productID' name='gr_warehouse[]' onchange=changewarehouse($productID,$itemID,$qty)>";
        } else {
            $selectHtml = "<select style='width:120px' class='required-entry required-entry select warehouse_select' id='gr_warehouse_$productID' name='gr_warehouse[]' disabled='disabled'>";
        }
        if (!empty($getData[0])) {
            for ($i = 0; $i < count($getData); $i++) {
                if ($getData[$i]['warehouse_id'] == $warehouseID) {
                    $selectHtml .= "<option value='" . $getData[$i]['warehouse_id'] . "' selected='selected'>" . $this->WarehouseFactory->load($getData[$i]['warehouse_id'])->getWarehouseName() . "</option>";
                } else {
                    $selectHtml .= "<option value='" . $getData[$i]['warehouse_id'] . "'>" . $this->WarehouseFactory->load($getData[$i]['warehouse_id'])->getWarehouseName() . "</option>";
                }
            }
        } else {
            $selectHtml .= "<option value='" . $defaultWarehouseID . "'>" . $this->WarehouseFactory->load($defaultWarehouseID)->getWarehouseName() . "</option>";
        }
        $selectHtml .= "</select>";
        return $selectHtml;
    }
}
