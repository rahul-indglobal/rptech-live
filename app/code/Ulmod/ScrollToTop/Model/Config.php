<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\ScrollToTop\Model;

use Ulmod\ScrollToTop\Model\System\Config\Backend\Image\Img as BackendImg;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Config
{

 
    /**
     * Path to store config
     *
     * @var string|int|bool
     */
    const XML_PATH_STP_GENERAL_ENABLED = 'umscrolltotop/general/enabled';

    const XML_PATH_STP_PAGE_HIDE_HOME = 'umscrolltotop/page/hide_home';
    const XML_PATH_STP_PAGE_HIDE_CMS = 'umscrolltotop/page/hide_cms';
    const XML_PATH_STP_PAGE_HIDE_CATEGORY = 'umscrolltotop/page/hide_category';
    const XML_PATH_STP_PAGE_HIDE_PRODUCT = 'umscrolltotop/page/hide_product';
    const XML_PATH_STP_PAGE_HIDE_ADDITIONAL = 'umscrolltotop/page/hide_additional';
    const XML_PATH_STP_PAGE_ADDITIONAL_PAGES = 'umscrolltotop/page/additional_pages';
    
    const XML_PATH_STP_DESIGN_TYPE = 'umscrolltotop/design/type';
    const XML_PATH_STP_DESIGN_ARROW = 'umscrolltotop/design/arrow';
    const XML_PATH_STP_DESIGN_IMAGE = 'umscrolltotop/design/image';
    const XML_PATH_STP_DESIGN_TEXT = 'umscrolltotop/design/text';
    const XML_PATH_STP_DESIGN_LABEL = 'umscrolltotop/design/label';

    const XML_PATH_STP_DESIGN_POSITION = 'umscrolltotop/design/position';
    
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;
    
    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
    }

   /**
    * Get current url
    *
    * @return string
    */
    public function getCurrentUrl()
    {
        return $this->urlBuilder->getCurrentUrl();
    }

    /**
     * Get System Config values
     *
     * @return string|int|array|null
     */
    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is the module enabled in configuration.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_GENERAL_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is hide on homepage
     *
     * @return bool
     */
    public function isHideOnHome()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_PAGE_HIDE_HOME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is hide on cms pages
     *
     * @return bool
     */
    public function isHideOnCms()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_PAGE_HIDE_CMS,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Is hide on category page
     *
     * @return bool
     */
    public function isHideOnCategory()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_PAGE_HIDE_CATEGORY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is hide on product page
     *
     * @return bool
     */
    public function isHideOnProduct()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_PAGE_HIDE_PRODUCT,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Is hide on additional page
     *
     * @return bool
     */
    public function isHideOnAdditional()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_PAGE_HIDE_ADDITIONAL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Additional pages
     *
     * @return string
     */
    public function getAdditionalPages()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_PAGE_ADDITIONAL_PAGES,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     *  Get scroll position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_DESIGN_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     *  Get scroll type
     *
     * @return string
     */
    public function getScrollType()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_DESIGN_TYPE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Get scroll button label
     *
     * @return string
     */
    public function getScrollLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_DESIGN_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     *  Get scroll arrow
     *
     * @return string
     */
    public function getScrollArrow()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_DESIGN_ARROW,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     *  Get scroll text
     *
     * @return string
     */
    public function getScrollText()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_DESIGN_TEXT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Get scroll image
     *
     * @return string
     */
    public function getScrollImage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STP_DESIGN_IMAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Get uploaded image url
     *
     * @return bool
     */
    public function getImgUrl()
    {
         $designConfig = $this->getScrollImage();
         $imgFolderName = BackendImg::UPLOAD_DIR;
         $imgPath = $imgFolderName . '/' . $designConfig;
         $imgUrl = $this->getBaseUrl() . $imgPath;

         return $imgUrl;
    }
}
