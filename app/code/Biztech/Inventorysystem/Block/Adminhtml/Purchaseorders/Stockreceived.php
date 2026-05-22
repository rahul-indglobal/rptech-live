<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Biztech\Inventorysystem\Model\Purchaseorders;
use Magento\Catalog\Helper\Image as CatalogImageHelper;
use Magento\Catalog\Model\Product;
use Biztech\Inventorysystem\Model\ManagesupplierFactory;
use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Biztech\Inventorysystemadvance\Model\Warehouse;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Stockreceived extends Container
{

    protected $_purchaseOrders;
    protected $productModel;
    protected $imageHelper;
    protected $_supplierModelFactory;
    protected $_bizHelper;
    protected $_resource;
    protected $_warehouseModel;
    protected $scopeConfig;
    protected $_pricingHelper;
    protected $_currentStore;
    protected $_currencyInterface;

    /**
     * @param Context                                     $context
     * @param Purchaseorders                              $purchaseOrders
     * @param Product                                     $productModel
     * @param CatalogImageHelper                          $imageHelper
     * @param ManagesupplierFactory                       $supplierModelFactory
     * @param BizHelper                                   $bizHelper
     * @param \Magento\Framework\Pricing\Helper\Data      $pricingHelper
     * @param \Magento\Store\Model\Store                  $currentStore
     * @param \Magento\Framework\Locale\CurrencyInterface $currencyInterface
     * @param Warehouse                                   $warehouseModel
     */
    public function __construct(
        Context $context,
        Purchaseorders $purchaseOrders,
        Product $productModel,
        CatalogImageHelper $imageHelper,
        ManagesupplierFactory $supplierModelFactory,
        BizHelper $bizHelper,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Store\Model\Store $currentStore,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        Warehouse $warehouseModel
    ) {
        parent::__construct($context);
        $this->_purchaseOrders = $purchaseOrders;
        $this->productModel = $productModel;
        $this->_supplierModelFactory = $supplierModelFactory;
        $this->imageHelper = $imageHelper;
        $this->_bizHelper = $bizHelper;
        $this->_resource = $bizHelper->getResource();
        $this->scopeConfig = $context->getScopeConfig();
        $this->_pricingHelper = $pricingHelper;
        $this->_currentStore = $currentStore;
        $this->_currencyInterface = $currencyInterface;
        $this->_warehouseModel = $warehouseModel;
    }

    /**
     * Supplier name
     * @param  int $supID
     * @return String
     */
    public function getSupplierFullName($supID)
    {
        $supDetails = $this->getSupplierDetails($supID);
        return $supDetails->getFirstName() . ' ' . $supDetails->getLastName();
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
     * Supplier details
     * @param  int $supID
     * @return Object
     */
    protected function getSupplierDetails($supID)
    {
        return $this->getSuppliers()->load($supID);
    }

    /**
     * Supplier details
     * @return Object
     */
    protected function getSuppliers()
    {
        return $this->_supplierModelFactory->create();
    }

    /**
     * PO details
     * @return Object
     */
    public function getPOModel()
    {
        return $this->_purchaseOrders;
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
     * Warehouse name
     * @param  int $productId
     * @param  object $warehouse
     * @return String
     */
    public function getWareHouseName($productId, $warehouse)
    {
        $html = '';
        if ($this->getBizHelper()->isModuleEnabled('Biztech_Inventorysystemadvance')) {
            $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $connection = $this->_resource->getConnection();
            $tableName = $this->_resource->getTableName('bc_warehouse_product_is');
            $getIncrIds = $connection->select()
                ->from($tableName, ['warehouse_id'])
                ->where('product_id = ' . $productId);
            $getData = $connection->fetchAll($getIncrIds);
            $html = "<select class='admin__control-select required-entry warehouse_select' id='warehouse_{$productId}' name='warehouse[]'>";
            if (!empty($getData[0])) {
                for ($j = 0; $j < count($getData); $j++) {
                    if ($getData[$j]['warehouse_id'] == $warehouse) {
                        $html .= '<option selected="selected" value="' . $getData[$j]['warehouse_id'] . '">' . $this->_warehouseModel->load($getData[$j]['warehouse_id'])->getWarehouseName() . "</option>";
                    } else {
                        $html .= '<option value="' . $getData[$j]['warehouse_id'] . '">' . $this->_warehouseModel->load($getData[$j]['warehouse_id'])->getWarehouseName() . "</option>";
                    }
                }
            } else {
                $html .= '<option value="' . $defaultWarehouseID . '">' . $this->_warehouseModel->load($defaultWarehouseID)->getWarehouseName() . "</option>";
            }
        }
        return $html;
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

        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
        $this->buttonList->remove('save');

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
     * Pricing heloer
     * @return Object
     */
    public function getPriceingHelper()
    {
        return $this->_pricingHelper;
    }

    /**
     * Current store
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
}
