<?php

namespace Rptech\Pincode\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface as scopeConfig;

class Data extends AbstractHelper
{      
       protected $_scopeConfig;

       public function __construct(scopeConfig $scopeConfig)
       {
              $this->_scopeConfig = $scopeConfig;
       }

       public function getApiEndpoint()
       {
              return $this->getConfig('api_endpoint');
       }

       public function getApiKey()
       {
              return $this->getConfig('api_key');
       }

       public function getApiToken()
       {
              return $this->getConfig('api_token');
       }

       public function getSuccessMessage()
       {
              return $this->getConfig('success_msg');
       }

       public function getFailedMessage()
       {
              return $this->getConfig('fail_msg');
       }

       public function getConfig($config_path)
       {
              return $this->_scopeConfig->getValue('pincodeChecker/general/'.$config_path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
       }
}