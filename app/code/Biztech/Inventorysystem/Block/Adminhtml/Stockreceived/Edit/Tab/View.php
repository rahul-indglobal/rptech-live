<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Stockreceived\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Biztech\Inventorysystem\Model\Stockreceived;
use Magento\Catalog\Helper\Image as CatalogImageHelper;
use Magento\Catalog\Model\Product;
use Biztech\Inventorysystem\Model\ManagesupplierFactory;

class View extends Container
{
    protected $_stockReceived;
    protected $productModel;
    protected $imageHelper;
    protected $_supplierModelFactory;
    private $_pricingHelper;
    private $_currencyCode;
    private $_localeCurrency;
    private $_bizHelper;
    protected $_warehouseModel;

    /**
     * @param Context                                         $context
     * @param Stockreceived                                   $stockReceived
     * @param Product                                         $productModel
     * @param CatalogImageHelper                              $imageHelper
     * @param ManagesupplierFactory                           $supplierModelFactory
     * @param \Magento\Framework\Pricing\Helper\Data          $pricingHelper
     * @param \Magento\Store\Model\Store                      $currencyCode
     * @param \Magento\Framework\Locale\CurrencyInterface     $localeCurrency
     * @param \Biztech\Inventorysystem\Helper\Data            $bizHelper
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     */
    public function __construct(
        Context $context,
        Stockreceived $stockReceived,
        Product $productModel,
        CatalogImageHelper $imageHelper,
        ManagesupplierFactory $supplierModelFactory,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Store\Model\Store $currencyCode,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Biztech\Inventorysystem\Helper\Data $bizHelper,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
    ) {
        parent::__construct($context);
        $this->_stockReceived = $stockReceived;
        $this->productModel = $productModel;
        $this->imageHelper = $imageHelper;
        $this->_supplierModelFactory = $supplierModelFactory;
        $this->_pricingHelper = $pricingHelper;
        $this->_currencyCode = $currencyCode;
        $this->_localeCurrency = $localeCurrency;
        $this->_bizHelper = $bizHelper;
        $this->_warehouseModel = $warehouseModel;
    }

    /**
     * Stock received data
     * @return Object
     */
    public function getSRModel()
    {
        return $this->_stockReceived;
    }

    /**
     * Supplier full name
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
     * @param  itn $supID
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
    protected function getWarehouseName($warehouseId)
    {
        $whDetails = $this->getWarehouseDetails($warehouseId);
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
     * Stock received
     * @param  int $id
     * @return Object
     */
    public function loadStockReceived($id)
    {
        return $this->getSRModel()->load($id, 'stockreceived_id');
    }

    /**
     * Supplier product image
     * @param  itn $productId
     * @return String
     */
    public function getProductImage($productId)
    {
        $product = $this->productModel->load($productId);
        $imageUrl = $this->imageHelper->init($product, 'product_listing_thumbnail');
        $imageUrl
                ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                ->setImageFile($product->getImage());

        $productImage = $imageUrl->getUrl();

        $width = $imageUrl->getWidth() ? $imageUrl->getWidth() : 90;
        $height = $imageUrl->getHeight() ? $imageUrl->getHeight() : 90;

        return '<img class="admin__control-thumbnail" src="' . $productImage . '" width="' . $width . '" height="' . $height . '" alt="' . $imageUrl->getLabel() . '"/>';
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
    public function getPricingHelper()
    {
        return $this->_pricingHelper;
    }

    /**
     * Currency code
     * @return Object
     */
    public function getCurrencyCode()
    {
        return $this->_currencyCode;
    }

    /**
     * Local currency
     * @return Object
     */
    public function getLocaleCurrency()
    {
        return $this->_localeCurrency;
    }

    /**
     * Biztech helper
     * @return Object
     */
    public function getBiztechHelper()
    {

        return $this->_bizHelper;
    }
}
