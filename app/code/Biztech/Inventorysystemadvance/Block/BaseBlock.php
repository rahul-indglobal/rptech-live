<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block;

use Magento\Framework\UrlFactory;

class BaseBlock extends \Magento\Framework\View\Element\Template
{
    protected $_devToolHelper;
    protected $_urlApp;
    protected $_config;

    /**
     * @param \Biztech\Inventorysystemadvance\Block\Context $context [description]
     */
    public function __construct(
        \Biztech\Inventorysystemadvance\Block\Context $context
    ) {
        $this->_devToolHelper = $context->getInventorysystemadvanceHelper();
        $this->_config = $context->getConfig();
        $this->_urlApp=$context->getUrlFactory()->create();
        parent::__construct($context);
    }
    
    /**
     * Function for getting event details
     * @return array
     */
    public function getEventDetails()
    {
        return  $this->_devToolHelper->getEventDetails();
    }
    
    /**
     * Function for getting current url
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->_urlApp->getCurrentUrl();
    }
    
    /**
     * Function for getting controller url for given router path
     * @param string $routePath
     * @return string
     */
    public function getControllerUrl($routePath)
    {
        
        return $this->_urlApp->getUrl($routePath);
    }
    
    /**
     * Function for getting current url
     * @param string $path
     * @return string
     */
    public function getConfigValue($path)
    {
        return $this->_config->getCurrentStoreConfigValue($path);
    }
    
    /**
     * Function canShowInventorysystemadvance
     * @return bool
     */
    public function canShowInventorysystemadvance()
    {
        $isEnabled=$this->getConfigValue('inventorysystemadvance/module/is_enabled');
        if ($isEnabled) {
            $allowedIps=$this->getConfigValue('inventorysystemadvance/module/allowed_ip');
            if (is_null($allowedIps)) {
                return true;
            } else {
                $remoteIp=$_SERVER['REMOTE_ADDR'];
                if (strpos($allowedIps, $remoteIp) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}
