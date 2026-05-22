<?php

namespace Brainvire\MissingOrder\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

	const IS_ENABLE = 'missingorder/missingordergrp/enable';

	protected $logger;
	protected $scopeConfig;
	protected $storeManager;
	protected $productFactory;
	protected $stockState;

	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
		\Psr\Log\LoggerInterface $logger,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Model\ProductFactory $productFactory,
		\Magento\CatalogInventory\Api\StockStateInterface $stockState
	) {
		$this->logger = $logger;
		$this->scopeConfig = $scopeConfig;
		$this->_storeManager = $storeManager;
		$this->productFactory = $productFactory;
		$this->stockState = $stockState;
		parent::__construct($context);
	}
	public function isModuleEnabled() {
		return $this->scopeConfig->getValue(self::IS_ENABLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	public function updateQty($productId, $qtyOrdered, $type) {
		$currentQty = $this->getStockItem($productId, $this->getCurrentWebsiteId());
		if ($type == 'plus') {
			$newQty = $currentQty + $qtyOrdered;
		}
		if ($type == 'minus') {
			$newQty = $currentQty - $qtyOrdered;
		}
		$product = $this->productFactory->create();
		// $productId = $product->getIdBySku($sku);
		// if ($productId) {
		$product->load($productId);
		// }
		$product->setStockData(
			array(
				'use_config_manage_stock' => 0,
				'manage_stock' => 1,
				'is_in_stock' => 1,
				'qty' => $newQty,
			)
		);
		try {
			$product->save();
		} catch (Exception $e) {
			echo $e->getException();
		}
	}
	public function getStockItem($productId, $websiteId) {
		return $this->stockState->getStockQty($productId, $websiteId);
	}

	public function getCurrentWebsiteId() {
		return $this->_storeManager->getStore()->getWebsiteId();
	}
	public function checkOutOfStockProductInCart($productId) {
		return $this->stockState->getStockQty($productId, $this->getCurrentWebsiteId());
	}
}
