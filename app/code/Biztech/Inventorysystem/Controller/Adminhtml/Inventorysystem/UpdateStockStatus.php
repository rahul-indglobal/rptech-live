<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\Inventorysystem;

use Magento\Framework\Module\Manager;
use Magento\Framework\App\Config\ScopeConfigInterface;

class UpdateStockStatus extends \Magento\Backend\App\Action
{

    protected $moduleManager;
    protected $scopeConfig;
    protected $_product;
    protected $_stockStateInterface;
    protected $_stockRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context                  $context
     * @param Manager                                              $moduleManager
     * @param ScopeConfigInterface                                 $scopeConfig
     * @param \Magento\Catalog\Model\Product                       $product
     * @param \Magento\CatalogInventory\Api\StockStateInterface    $stockStateInterface
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Manager $moduleManager,
        ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product $product,
        \Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {

        $this->moduleManager = $moduleManager;
        $this->scopeConfig = $scopeConfig;
        $this->_product = $product;
        $this->_stockStateInterface = $stockStateInterface;
        $this->_stockRegistry = $stockRegistry;
        parent::__construct($context);
    }

    /**
     * This function used for the update the selected products stock and status
     * @return void
     */
    public function execute()
    {
        try {
            $prodIds = $this->getRequest()->getParam('inventorysystem');
            if ($prodIds && is_array($prodIds) && !empty($prodIds)) {
                $notUpdateFlag = 0;
                $updateFlag = 0;
                
                for ($i = 0; $i < count($prodIds); $i++) {
                    $product=$this->_product->load($prodIds[$i]);
                    $stockItem=$this->_stockRegistry->getStockItem($prodIds[$i]);
                    
                    if ($this->getRequest()->getParam('status') == 2) {
                        $stockStatus = 0;
                    } else {
                        $stockStatus = 1;
                    }
                    $getOutOfStckQty = $this->scopeConfig->getValue('cataloginventory/item_options/min_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $curQty = $stockItem['qty'];
                    $backorders = $stockItem->getBackorders();
                    
                    /* if the qty of product is less than the qty defined to be out of stock & if the backorders are not allowed, stock status will not change */
                    if ($curQty <= $getOutOfStckQty && $backorders == 0) {
                        $notUpdateFlag++;
                        continue;
                    }
                    $stockItem->setData('is_in_stock', $stockStatus);
                    $stockItem->save();
                    $updateFlag++;
                }
                if ($updateFlag > 0) {
                    $this->messageManager->addSuccess(__('Stock Status of total %1 product(s) were successfully updated', $updateFlag));
                }
                if ($notUpdateFlag > 0) {
                    $this->messageManager->addError(__('Stock Status of total %1 product(s) were not updated as their quantity was less or equal to the limit for the product to be Out of Stock', $notUpdateFlag));
                }
            }
            $this->_redirect('*/*/index');
        } catch (\Magento\Framework\Model\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }
}
