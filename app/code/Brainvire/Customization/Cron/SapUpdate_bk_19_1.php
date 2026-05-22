<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brainvire\Customization\Cron;

class SapUpdate
{
    const API_URL = 'brv_customization/general/api_url';
    const PLANT_LOC = 'brv_customization/general/plant_loc';

    /**
     * [__construct description]
     * @param \Magento\Integration\Model\Oauth\TokenFactory        $tokenModelFactory [description]
     * @param \Magento\Framework\App\Config\ScopeConfigInterface   $scopeConfig       [description]
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry     [description]
     * @param \Magento\Catalog\Model\ProductFactory                $productFactory    [description]
     * @param \Magento\CatalogInventory\Helper\Stock               $stockFilter       [description]
     * @param array                                                $data              [description]
     */
    public function __construct(
        \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogInventory\Helper\Stock $stockFilter,
        array $data = []
    ) {
        $this->tokenModelFactory = $tokenModelFactory;
        $this->scopeConfig = $scopeConfig;
        $this->stockRegistry = $stockRegistry;
        $this->productFactory = $productFactory;
        $this->stockFilter = $stockFilter;
    }

    public function execute()
    {

        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('Cron Executing'); // Simple Text Log

        $collection = $this->productFactory->create()->getCollection();
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $apiUrl = $this->scopeConfig->getValue(self::API_URL, $storeScope);
        $plantLoc = $this->scopeConfig->getValue(self::PLANT_LOC, $storeScope);
        $plantLocData = json_decode($plantLoc, true);

        $qty = 0;
        //Get Response from API
        if (!empty($plantLocData)) {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);

            foreach ($collection->getData() as $key => $proData) {
                foreach ($plantLocData as $key => $value) {
                    $postData = '&materialcode=' . $proData['sku'] . '&plant=' . $value['plant'] . '&storageloc=' . $value['storageloc'];
                    $ch = curl_init($apiUrl . $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    if (!is_string($response) || !strlen($response)) {
                        $logger->info('Not able to connect on Server');
                    } else {
                        $result = json_decode($response, true);
                        if ($result === false) {
                            $logger->info('JSON is invalid');
                        } else {
                            if ($result[0]['LABST']) {
                                $qty = $result[0]['LABST'];
                                $stockItem = $this->stockRegistry->getStockItemBySku($proData['sku']);
                                $stockItem->setQty($qty);
                                $stockItem->setIsInStock((bool) $qty);
                                $this->stockRegistry->updateStockItemBySku($proData['sku'], $stockItem);

                                $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
                                $logger = new \Zend_Log();
                                $logger->addWriter($writer);
                                $logger->info('Updated sku ' . $proData['sku']); // Simple Text Log
                            }
                        }
                    }
                    curl_close($ch);
                }
                //Update Product Qty.
            }
        }
    }
}
