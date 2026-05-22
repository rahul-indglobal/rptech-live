<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model;

class Config extends \Magento\Framework\DataObject
{
    protected $_storeManager;
    protected $_scopeConfig;
    protected $_backendModel;
    protected $_transaction;
    protected $_configValueFactory;
    protected $_storeId;
    protected $_storeCode;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Config\ValueInterface       $backendModel
     * @param \Magento\Framework\DB\Transaction                  $transaction
     * @param \Magento\Framework\App\Config\ValueFactory         $configValueFactory
     * @param array                                              $data
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\ValueInterface $backendModel,
        \Magento\Framework\DB\Transaction $transaction,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_backendModel = $backendModel;
        $this->_transaction = $transaction;
        $this->_configValueFactory = $configValueFactory;
        $this->_storeId=(int)$this->_storeManager->getStore()->getId();
        $this->_storeCode=$this->_storeManager->getStore()->getCode();
    }
    
    /**
     * Function for getting Config value of current store
     * @param string $path,
     */
    public function getCurrentStoreConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path, 'store', $this->_storeCode);
    }
    
    /**
     * Function for setting Config value of current store
     * @param string $path,
     * @param string $value,
     */
    public function setCurrentStoreConfigValue($path, $value)
    {
        $data = [
                    'path' => $path,
                    'scope' =>  'stores',
                    'scope_id' => $this->_storeId,
                    'scope_code' => $this->_storeCode,
                    'value' => $value,
                ];

        $this->_backendModel->addData($data);
        $this->_transaction->addObject($this->_backendModel);
        $this->_transaction->save();
    }
}
