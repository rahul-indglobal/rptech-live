<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brainvire\Customization\Block;

class UpdateQty extends \Magento\Backend\Block\Template
{
    const API_URL = 'brv_customization/general/api_url';
    const PLANT_LOC = 'brv_customization/general/plant_loc';

    /**
     * [__construct description]
     * @param \Magento\Backend\Block\Template\Context              $context           [description]
     * @param \Magento\Framework\Registry                          $registry          [description]
     * @param \Magento\Integration\Model\Oauth\TokenFactory        $tokenModelFactory [description]
     * @param \Magento\Framework\App\Config\ScopeConfigInterface   $scopeConfig       [description]
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry     [description]
     * @param array                                                $data              [description]
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->tokenModelFactory = $tokenModelFactory;
        $this->scopeConfig = $scopeConfig;
        $this->stockRegistry = $stockRegistry;
        parent::__construct($context, $data);
    }

    /*public function updateQty()
    {
    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    $apiUrl = $this->scopeConfig->getValue(self::API_URL, $storeScope);
    $plantLoc = $this->scopeConfig->getValue(self::PLANT_LOC, $storeScope);
    $plantLocData = json_decode($plantLoc, true);
    $proSku = $this->registry->registry('current_product')->getSku();

    $qty = 0;
    //Get Response from API
    if (!empty($plantLocData)) {
    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/UpdateQty.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    foreach ($plantLocData as $key => $value) {
    $postData = '&materialcode=' . $proSku . '&plant=' . $value['plant'] . '&storageloc=' . $value['storageloc'];
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
    $qty += $result[0]['LABST'];
    }
    }
    }
    curl_close($ch);
    }
    //Update Product Qty.
    if ($qty > 0) {
    $stockItem = $this->stockRegistry->getStockItemBySku($proSku);
    //$proQty = $stockItem->getQty();
    //$totalQty = $proQty + $qty;
    $stockItem->setQty($qty);
    $stockItem->setIsInStock((bool) $qty);
    $this->stockRegistry->updateStockItemBySku($proSku, $stockItem);
    }
    }
    }*/
    //Get SAP Qty
    public function sapQty()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $apiUrl = $this->scopeConfig->getValue(self::API_URL, $storeScope);
        $plantLoc = $this->scopeConfig->getValue(self::PLANT_LOC, $storeScope);
        $plantLocData = json_decode($plantLoc, true);
        $proSku = $this->registry->registry('current_product')->getSku();

        $qty = 0;
        //Get Response from API
        if (!empty($plantLocData)) {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            foreach ($plantLocData as $key => $value) {
                $postData = '&materialcode=' . $proSku . '&plant=' . $value['plant'] . '&storageloc=' . $value['storageloc'];
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
                        if (!empty($result[0]['LABST'])) {
                            $qty += $result[0]['LABST'];
                        }
                    }
                }
                curl_close($ch);
            }
            if ($qty) {
                return $qty;
            } else {
                return '';
            }
        }
    }

}
