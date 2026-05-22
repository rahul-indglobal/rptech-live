<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders;

use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Biztech\Inventorysystem\Model\ManagesupplierFactory;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Catalog\Helper\Image as CatalogImageHelper;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class Purchaseorderform extends Container
{
    protected $_bizHelper;
    protected $_productFactory;
    protected $_manageSupplierFactory;
    protected $_productConfig;
    protected $_eavAttribute;
    protected $_stockItem;
    protected $_imageHelper;
    protected $_orderCollectionFactory;
    protected $_resource;
    protected $_pricingHelper;
    protected $_currentStore;
    protected $_currencyInterface;

    /**
     * @param Context                                     $context
     * @param BizHelper                                   $bizHelper
     * @param ManagesupplierFactory                       $managerSupplierFactory
     * @param ProductFactory                              $productFactory
     * @param Config                                      $config
     * @param Attribute                                   $eavAttribute
     * @param StockStateInterface                         $stockItem
     * @param CatalogImageHelper                          $imageHelper
     * @param OrderCollectionFactory                      $orderCollectionFactory
     * @param \Magento\Framework\App\ResourceConnection   $resource
     * @param \Magento\Framework\Pricing\Helper\Data      $pricingHelper
     * @param \Magento\Store\Model\Store                  $currentStore
     * @param \Magento\Framework\Locale\CurrencyInterface $currencyInterface
     */
    public function __construct(
        Context $context,
        BizHelper $bizHelper,
        ManagesupplierFactory $managerSupplierFactory,
        ProductFactory $productFactory,
        Config $config,
        Attribute $eavAttribute,
        StockStateInterface $stockItem,
        CatalogImageHelper $imageHelper,
        OrderCollectionFactory $orderCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Store\Model\Store $currentStore,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface
    ) {
        $this->_bizHelper = $bizHelper;
        parent::__construct($context);
        $this->_productFactory = $productFactory;
        $this->_manageSupplierFactory = $managerSupplierFactory;
        $this->_productConfig = $config;
        $this->_eavAttribute = $eavAttribute;
        $this->_stockItem = $stockItem;
        $this->_imageHelper = $imageHelper;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_resource = $resource;
        $this->_pricingHelper = $pricingHelper;
        $this->_currentStore = $currentStore;
        $this->_currencyInterface = $currencyInterface;
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('inventorysystem_purchaseorders_data')->getId()) {
            return __("Edit Item '%1'", $this->escapeHtml($this->_coreRegistry->registry('inventorysystem_purchaseorders_data')->getTitle()));
        } else {
            return __('Generate PO');
        }
    }

    /**
     * @return array
     *
     *
     */
    public function getPendingItemDetails()
    {
        $itemsArray = [];
        $connection = $this->_bizHelper->getResource()->getConnection();

        if ($this->getRequest()->getPostValue('massaction_prepare_key') == 'pendingitems') {
            $prodCost = 0;
            $productIds = $this->getRequest()->getPostValue('pendingitems');
            $nameAttributeId = $this->getAttributeIdByCode('name');
            $costAttributeId = $this->getAttributeIdByCode('cost');

            $getSuppConfig = $this->_bizHelper->getConfig('inventorysystem/supplierconfig/showsupplier');

            foreach ($productIds as $productId) {
                $productCollection = $this->_productFactory->create()->getCollection();
                $productCollection
                    // ->addAttributeToSelect($this->_productConfig->getProductAttributes())
                    ->addAttributeToSelect(['entity_id', 'name', 'cost'])
                    ->addAttributeToFilter('entity_id', $productId);

                $productCollection->getSelect()->join(
                    ['cpev' => $this->_resource->getTableName('catalog_product_entity_varchar')],
                    'e.entity_id=cpev.entity_id AND cpev.attribute_id=' . $nameAttributeId,
                    ['value as name']
                );

                $productCollection->getSelect()->joinLeft(
                    ['cped' => $this->_resource->getTableName('catalog_product_entity_decimal')],
                    'e.entity_id=cped.entity_id AND cped.attribute_id=' . $costAttributeId,
                    ['value as cost']
                );

                $productCollection->getSelect()->join(
                    ['sup_prod' => $this->_resource->getTableName(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplier::TBL_ATT_PRODUCT)],
                    'e.entity_id=sup_prod.product_id',
                    ['GROUP_concat(DISTINCT(supplier_id)) AS supplier_id']
                );

                $productCollection->getSelect()->group('e.entity_id');

                $productsData = $productCollection->getData();

                if (empty($productsData)) {
                    $productData = $this->_productFactory->create()->load($productId);

                    $prodName = $productData->getName();
                    $prodSku = $productData->getSku();
                    // $prodPrice = $productData->getPrice();
                    $prodCost = $productData->getCost();

                    $supplierCollection = $this->_manageSupplierFactory->create()->getCollection()
                        ->addFieldToFilter('is_active', 1)
                        ->setOrder('first_name', 'ASC');
                    $supplierData = $supplierCollection->getData();
                    $supplierHtml = $this->_bizHelper->getSupplierName($supplierData, '', $productId);
                } else {
                    $prodName = $productsData['0']['name'];
                    $prodSku = $productsData['0']['sku'];
                    // $prodPrice = $productsData['0']['price'];
                    $prodCost = $productsData['0']['cost'];

                    if ($getSuppConfig == 1) {
                        $supplierCollection = $this->_manageSupplierFactory->create()->getCollection()
                            ->addFieldToFilter('is_active', 1);
                    } else {
                        $supplierCollection = $this->_manageSupplierFactory->create()->getCollection()
                            ->addFieldToFilter('is_active', 1)
                            ->addFieldToFilter('supplier_id', ['in' => explode(',', $productsData[0]['supplier_id'])]);
                    }
                    $supplierData = $supplierCollection->getData();
                    $supplierHtml = $this->_bizHelper->getSupplierName($supplierData, $productsData[0]['supplier_id'], $productId);
                }

                $warehouseHtml = $this->_bizHelper->getWareHouseName($productId);

                $product = $this->_productFactory->create()->load($productId);
                $productStockData = $this->_stockItem->getStockQty($productId, $product->getStore()->getWebsiteId());

                $imageUrl = $this->_imageHelper->init($product, 'product_listing_thumbnail');
                $imageUrl
                    ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                    ->setImageFile($product->getImage());
                $productImage = $imageUrl->getUrl();

                $last_3_months_sale = $this->lastThreeMonthSaleProdQty($prodSku);

                $itemsArray[$productId] = [
                    'name' => $prodName,
                    'image' => $productImage,
                    'sku' => $prodSku,
                    // 'price' => $prodPrice,
                    'cost' => $prodCost,
                    'qty' => $productStockData,
                    'supplier' => $supplierHtml,
                    'warehouse' => $warehouseHtml,
                    'last_3_months_sale' => $last_3_months_sale[0]['last_3_months_sale_qty']
                ];
            }
        }
        // exit;
        return $itemsArray;
    }

    /**
     * @param $attributeCode
     * @return int
     */
    protected function getAttributeIdByCode($attributeCode)
    {
        return $this->_eavAttribute->getIdByCode('catalog_product', $attributeCode);
    }

    /**
     * @param $prodSku
     * @return mixed
     */
    protected function lastThreeMonthSaleProdQty($prodSku)
    {
        $connection = $this->_bizHelper->getResource()->getConnection();
        $productCollection = $this->_productFactory->create()->getCollection()
            ->addAttributeToSelect(['sku'])
            ->addFieldToFilter('sku', $prodSku);

        $productCollection->getSelect()->joinLeft(
            ['soi' => $this->_resource->getTableName('sales_order_item')],
            'e.entity_id=soi.product_id',
            ['SUM(soi.qty_ordered) AS last_3_months_sale_qty']
        );

        $productCollection->getSelect()->joinLeft(
            ['so' => $this->_resource->getTableName('sales_order')],
            'soi.order_id=so.entity_id'
        );

        $productCollection->getSelect()->where('so.created_at >= now() - INTERVAL 3 month');

        return $productCollection->getData();
    }

    /**
     * @return array
     */
    public function getOrderDetails()
    {
        $itemsArray = [];
        if ($this->getRequest()->getPostValue('massaction_prepare_key') == 'pendingorders') {
            $getOrderId = $this->getRequest()->getParam('pendingorders');
            sort($getOrderId);
            $orderArr = [];
            $connection = $this->_bizHelper->getResource()->getConnection();
            $nameAttributeId = $this->getAttributeIdByCode('name');
            $costAttributeId = $this->getAttributeIdByCode('cost');
            $getSuppConfig = $this->_bizHelper->getConfig('inventorysystem/supplierconfig/showsupplier');

            foreach ($getOrderId as $key => $orderId) {
                $_orderCollection = $this->_orderCollectionFactory->create();
                $_orderCollection->addFieldToFilter('entity_id', $orderId);
                $orderItemTable = $this->_resource->getTableName('sales_order_item');
                $_orderCollection->getSelect()->joinLeft(
                    ['order_item' => $orderItemTable],
                    'order_item.order_id = main_table.entity_id',
                    ['*']
                );

                foreach ($_orderCollection->getData() as $item) {
                    // var_dump($item);
                    if (isset($item['has_children'])) {
                        continue;
                    } else {
                        $productId = $item['product_id'];
                        $itemId = $item['increment_id'] . '_' . $productId;

                        $productCollection = $this->_productFactory->create()->getCollection();
                        $productCollection
                            // ->addAttributeToSelect($this->_productConfig->getProductAttributes())
                            ->addAttributeToSelect(['entity_id', 'name', 'cost'])
                            ->addAttributeToFilter('entity_id', $productId);

                        $productCollection->getSelect()->join(
                            ['cpev' => $this->_resource->getTableName('catalog_product_entity_varchar')],
                            'e.entity_id=cpev.entity_id AND cpev.attribute_id=' . $nameAttributeId,
                            ['value as name']
                        );
                        $connection = $this->_eavAttribute->getConnection();
                        $productCollection->getSelect()->joinLeft(
                            ['cped' => $this->_resource->getTableName('catalog_product_entity_decimal')],
                            'e.entity_id=cped.entity_id AND cped.attribute_id=' . $costAttributeId,
                            ['value as cost']
                        );

                        $productCollection->getSelect()->join(
                            ['sup_prod' => $this->_resource->getTableName(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplier::TBL_ATT_PRODUCT)],
                            'e.entity_id=sup_prod.product_id',
                            ['GROUP_concat(DISTINCT(supplier_id)) AS supplier_id']
                        );

                        $productCollection->getSelect()->group('e.entity_id');

                        $productsData = $productCollection->getData();
                        if (empty($productsData)) {
                            $productData = $this->_productFactory->create()->load($productId);

                            $prodName = $productData->getName();
                            $prodSku = $productData->getSku();
                            // $prodPrice = $productData->getPrice();
                            $prodCost = $productData->getCost();

                            $supplierCollection = $this->_manageSupplierFactory->create()->getCollection()
                                ->addFieldToFilter('is_active', 1)
                                ->setOrder('first_name', 'ASC');
                            $supplierData = $supplierCollection->getData();
                            $supplierHtml = $this->_bizHelper->getSupplierName($supplierData, '', $itemId);
                        } else {
                            $prodName = $productsData['0']['name'];
                            $prodSku = $productsData['0']['sku'];
                            // $prodPrice = $productsData['0']['price'];
                            $prodCost = $productsData['0']['cost'];

                            if ($getSuppConfig == 1) {
                                $supplierCollection = $this->_manageSupplierFactory->create()->getCollection()
                                    ->addFieldToFilter('is_active', 1);
                            } else {
                                $supplierCollection = $this->_manageSupplierFactory->create()->getCollection()
                                    ->addFieldToFilter('is_active', 1)
                                    ->addFieldToFilter('supplier_id', ['in' => explode(',', $productsData[0]['supplier_id'])]);
                            }
                            $supplierData = $supplierCollection->getData();
                            $supplierHtml = $this->_bizHelper->getSupplierName($supplierData, $productsData[0]['supplier_id'], $itemId);
                        }

                        $warehouseHtml = $this->_bizHelper->getWareHouseName($productId, $itemId);

                        $product = $this->_productFactory->create()->load($productId);
                        $productStockData = $this->_stockItem->getStockQty($productId, $product->getStore()->getWebsiteId());

                        $imageUrl = $this->_imageHelper->init($product, 'product_listing_thumbnail');
                        $imageUrl
                            ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                            ->setImageFile($product->getImage());
                        $productImage = $imageUrl->getUrl();

                        $last_3_months_sale = $this->lastThreeMonthSaleProdQty($prodSku);

                        $itemsArray[$item['increment_id']][$productId] = [
                            'name' => $prodName,
                            'image' => $productImage,
                            'sku' => $prodSku,
                            // 'price' => $prodPrice,
                            'qty' => $productStockData,
                            'qty_ordered' => $item['qty_ordered'],
                            'cost' => $prodCost,
                            'supplier' => $supplierHtml,
                            'warehouse' => $warehouseHtml,
                            'last_3_months_sale' => $last_3_months_sale[0]['last_3_months_sale_qty']
                        ];
                    }
                }
            }
        }

        return $itemsArray;
    }

    /**
     * @return BizHelper
     */
    public function getBizHelper()
    {
        return $this->_bizHelper;
    }

    /**
     * @return  Void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_controller = 'adminhtml_purchaseorders';

        if (!$this->getBizHelper()->isEnable()) {
        } else {
            parent::_construct();
            $this->buttonList->remove('save');
            $this->buttonList->remove('delete');
            $this->buttonList->remove('reset');

            if ($this->getRequest()->getPostValue('massaction_prepare_key') == 'pendingitems') {
                $this->buttonList->update('back', 'onclick', "setLocation('" . $this->getUrl('inventorysystem/pendingitems/index') . "')");
            } elseif ($this->getRequest()->getPostValue('massaction_prepare_key') == 'pendingorders') {
                // $this->setTemplate('Biztech_Inventorysystem::purchaseorders/createpo.phtml');
                $this->buttonList->update('back', 'onclick', "setLocation('" . $this->getUrl('inventorysystem/pendingorders/index') . "')");
            } else {
                $this->buttonList->update('back', 'onclick', "setLocation('" . $this->getUrl('inventorysystem/purchaseorders/index') . "')");
                $this->setTemplate('Biztech_Inventorysystem::purchaseorders/addpurchaseorderproduct.phtml');
            }

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
     * Current Store
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
