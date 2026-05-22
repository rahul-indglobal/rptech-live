<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Biztech\Inventorysystem\Model\Purchaseorders;
use Magento\Catalog\Helper\Image as CatalogImageHelper;
use Magento\Catalog\Model\ProductFactory;
use Biztech\Inventorysystem\Model\ManagesupplierFactory;
use Biztech\Inventorysystem\Helper\Data as BizHelper;

class View extends Container
{
    protected $_purchaseOrders;
    protected $productModelFactory;
    protected $imageHelper;
    protected $_supplierModelFactory;
    protected $_warehouseModel;
    protected $_bizHelper;
    protected $_pricingHelper;
    protected $_currentStore;
    protected $_currencyInterface;
    protected $_orderModel;

    /**
     * @param Context                                         $context
     * @param Purchaseorders                                  $purchaseOrders
     * @param ProductFactory                                  $productModelFactory
     * @param CatalogImageHelper                              $imageHelper
     * @param ManagesupplierFactory                           $supplierModelFactory
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     * @param BizHelper                                       $bizHelper
     * @param \Magento\Framework\Pricing\Helper\Data          $pricingHelper
     * @param \Magento\Store\Model\Store                      $currentStore
     * @param \Magento\Framework\Locale\CurrencyInterface     $currencyInterface
     * @param \Magento\Sales\Model\Order                      $orderModel
     */
    public function __construct(
        Context $context,
        Purchaseorders $purchaseOrders,
        ProductFactory $productModelFactory,
        CatalogImageHelper $imageHelper,
        ManagesupplierFactory $supplierModelFactory,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        BizHelper $bizHelper,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Store\Model\Store $currentStore,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        \Magento\Sales\Model\Order $orderModel
    ) {
        $this->_purchaseOrders = $purchaseOrders;
        $this->productModelFactory = $productModelFactory;
        $this->imageHelper = $imageHelper;
        $this->_supplierModelFactory = $supplierModelFactory;
        $this->_warehouseModel = $warehouseModel;
        $this->_bizHelper = $bizHelper;
        $this->_pricingHelper = $pricingHelper;
        $this->_currentStore = $currentStore;
        $this->_currencyInterface = $currencyInterface;
        $this->_orderModel = $orderModel;
        parent::__construct($context);
    }

    /**
     * Get product factory
     * @return Object
     */
    public function getProductModelFactory()
    {
        return $this->productModelFactory;
    }

    /**
     * Get PO
     * @return Object
     */
    public function getPOModel()
    {
        return $this->_purchaseOrders;
    }

    /**
     * Supplier fullname
     * @param  int $supID
     * @return String
     */
    public function getSupplierFullName($supID)
    {
        $supDetails = $this->getSupplierDetails($supID);
        return $supDetails->getFirstName() . ' ' . $supDetails->getLastName();
    }

    /**
     * Supplier details
     * @param  int $supID
     * @return Object
     */
    protected function getSupplierDetails($supID)
    {
        return $this->getSuppliers()->load($supID);
    }

    /**
     * Suppliers details
     * @return Object
     */
    protected function getSuppliers()
    {
        return $this->_supplierModelFactory->create();
    }

    /**
     * Warehouse name
     * @param  int $warehouseId
     * @return String
     */
    public function getWarehouseName($warehouseId)
    {
        $whDetails = $this->_purchaseOrders->load($warehouseId);
        return $whDetails->getWarehouseName();
    }

    /**
     * Warehouse details
     * @param  int $warehouseId
     * @return Object
     */
    protected function getWarehouseDetails($warehouseId)
    {
        return $this->getWarehouse()->load($warehouseId);
    }

    /**
     * Warehose details
     * @param  int $id
     * @return Object
     */
    public function getWarehouse($id)
    {
        return $this->_warehouseModel->load($id);
    }

    /**
     * Load PO
     * @param  int $id
     * @return Object
     */
    public function loadPurchaseOrder($id)
    {
        return $this->_purchaseOrders->load($id);
    }

    /**
     * Product image
     * @param  int $productId
     * @return String
     */
    public function getProductImage($productId)
    {
        $productModel = $this->productModelFactory->create();
        $product = $productModel->load($productId);
        $imageUrl = $this->imageHelper->init($product, 'product_listing_thumbnail');
        $imageUrl
                ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                ->setImageFile($product->getImage());

        $productImage = $imageUrl->getUrl();

        $width = $imageUrl->getWidth() ? $imageUrl->getWidth() : 90;
        $height = $imageUrl->getHeight() ? $imageUrl->getHeight() : 90;

        return '<img class="admin__control-thumbnail" src="' . $productImage . '" width="' . $width . '" height="' . $height . '" alt="' . $imageUrl->getLabel() . '"/>';
    }

    protected function getPOStatus()
    {
        $poID = $this->getRequest()->getParam('id');
        $poModel = $this->_purchaseOrders->load($poID);
        return $poModel->getStatus();
    }

    /**
     * @return Void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_controller = 'adminhtml_purchaseorders';

        parent::_construct();

        $this->buttonList->remove('save');
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');

        if ($this->getPOStatus() != 'completed' && $this->getPOStatus() != 'canceled') {
            $this->buttonList->add(
                'stock_received',
                array(
                    'label' => __('Stock Received'),
                    'class' => 'stock-received',
                    'onclick' => "setLocation('" . $this->getUrl('inventorysystem/purchaseorders/stockreceived', ['porder_id' => $this->getRequest()->getParam('id')]) . "')",
                ),
                -100
            );
        } else if ($this->getPOStatus() == 'completed' && $this->getPOStatus() != 'canceled') {
            $this->buttonList->add(
                'po_invoice',
                array(
                    'label' => __('Invoice'),
                    'class' => 'po_invoice',
                    'onclick' => "setLocation('" . $this->getUrl('inventorysystem/purchaseorders/createInvoice', ['porder_id' => $this->getRequest()->getParam('id')]) . "')",
                ),
                -100
            );
        }

        $this->buttonList->add(
            'reorder',
            array(
                'label' => __('Reorder'),
                'class' => 'reorder',
                'onclick' => "setLocation('" . $this->getUrl('inventorysystem/purchaseorders/reorder', ['porder_id' => $this->getRequest()->getParam('id')]) . "')",
            ),
            -100
        );

        $this->buttonList->add(
            'print',
            array(
                'label' => __('Print'),
                'class' => 'print',
                'onclick' => "setLocation('" . $this->getUrl('inventorysystem/purchaseorders/printpopdf', ['id' => $this->getRequest()->getParam('id')]) . "')",
            ),
            -100
        );

        $this->_formScripts[] = "
        function toggleEditor() {
            if (tinyMCE.getInstanceById('inventorysystem_content') == null) {
                tinyMCE.execCommand('mceAddControl', false, 'inventorysystem_content');
            } else {
                tinyMCE.execCommand('mceRemoveControl', false, 'inventorysystem_content');
            }
        }
        ";
    }

    /**
     * Pricing helper
     * @return Object
     */
    public function getPriceingHelper()
    {
        return $this->_pricingHelper;
    }

    /**
     * Current sore details
     * @return Object
     */
    public function getCurrentStore()
    {
        return $this->_currentStore;
    }

    /**
     * Currency interface
     * @return Object
     */
    public function getCurrencyInterface()
    {
        return $this->_currencyInterface;
    }

    /**
     * Biztech helper
     * @return Object
     */
    public function getBizHelper()
    {
        return $this->_bizHelper;
    }

    /**
     * Order details
     * @return Object
     */
    public function getOrder()
    {
        return $this->_orderModel;
    }
}
