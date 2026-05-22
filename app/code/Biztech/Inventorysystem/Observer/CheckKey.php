<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Observer;

use Biztech\Inventorysystem\Helper\Data;
use Magento\Config\Model\Config;
use Magento\Config\Model\Config\Factory;
use Magento\Config\Model\ResourceModel\Config as ResourceConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ValueFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;

class checkKey implements ObserverInterface
{

    const XML_PATH_ACTIVATIONKEY = 'inventorysystem/activation/key';
    const XML_PATH_DATA = 'inventorysystem/activation/data';

    protected $scopeConfig;
    protected $encryptor;
    protected $configFactory;
    protected $helper;
    protected $request;
    protected $resourceConfig;
    protected $configModel;
    protected $configValueFactory;
    protected $_cacheFrontendPool;
    protected $_cacheTypeList;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface   $encryptor
     * @param Factory              $configFactory
     * @param Data                 $helper
     * @param RequestInterface     $request
     * @param ResourceConfig       $resourceConfig
     * @param ValueFactory         $configValueFactory
     * @param Config               $configModel
     * @param Pool                 $cacheFrontendPool
     * @param TypeListInterface    $cacheTypeList
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor,
        Factory $configFactory,
        Data $helper,
        RequestInterface $request,
        ResourceConfig $resourceConfig,
        ValueFactory $configValueFactory,
        Config $configModel,
        Pool $cacheFrontendPool,
        TypeListInterface $cacheTypeList
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
        $this->configFactory = $configFactory;
        $this->helper = $helper;
        $this->request = $request;
        $this->resourceConfig = $resourceConfig;
        $this->configModel = $configModel;
        $this->configValueFactory = $configValueFactory;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_cacheTypeList = $cacheTypeList;
    }

    /**
     * This function is used for the check the key for the activate the extension
     * @param  \Magento\Framework\Event\Observer $observer
     * @return bool
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $k = $this->scopeConfig->getValue(self::XML_PATH_ACTIVATIONKEY, ScopeInterface::SCOPE_STORE);
        $s = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('https://store.biztechconsultancy.com/extension/licence.php'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=' . urlencode($k) . '&domains=' . urlencode(implode(',', $this->helper->getAllStoreDomains())) . '&sec=magento2-magemobinventory');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $content = curl_exec($ch);
        $res1 = json_decode($content);
        $res = (array)$res1;
        $moduleStatus = $this->resourceConfig;
        if (empty($res)) {
            $moduleStatus->saveConfig('inventorysystem/activation/key', "");
            $moduleStatus->saveConfig('inventorysystem/enableextension/enabled', 0);
            $data = $this->scopeConfig('inventorysystem/activation/data');
            $this->resourceConfig->saveConfig('inventorysystem/activation/data', $data, 'default', 0);
            $this->resourceConfig->saveConfig('inventorysystem/activation/websites', '', 'default', 0);
            return;
        }
        $data = '';
        $web = '';
        $en = '';
        if (isset($res['dom']) && intval($res['c']) > 0 && intval($res['suc']) == 1) {
            $data = $this->encryptor->encrypt(base64_encode(json_encode($res1)));
            if (!$s) {
                $params = $this->request->getParam('groups');
                if (isset($params['activation']['fields']['websites']['value'])) {
                    $s = $params['activation']['fields']['websites']['value'];
                }
            }
            $en = $res['suc'];
            if (isset($s) && $s != null) {
                $web = $this->encryptor->encrypt($data . implode(',', $s) . $data);
            } else {
                $web = $this->encryptor->encrypt($data . $data);
            }
        } else {
            $moduleStatus->saveConfig('inventorysystem/activation/key', "", 'default', 0);
            $moduleStatus->saveConfig('inventorysystem/enableextension/enabled', 0, 'default', 0);
        }
        $this->resourceConfig->saveConfig('inventorysystem/activation/data', $data, 'default', 0);
        $this->resourceConfig->saveConfig('inventorysystem/activation/websites', $web, 'default', 0);
        $this->resourceConfig->saveConfig('inventorysystem/activation/en', $en, 'default', 0);
        $this->resourceConfig->saveConfig('inventorysystem/activation/installed', 1, 'default', 0);

        //refresh config cache after save
        $types = ['config', 'full_page'];
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
