<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\ScrollToTop\Block;

use Magento\Framework\View\Element\Template\Context;
use Ulmod\ScrollToTop\Model\Config as ModelConfig;

class ScrollToTop extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ModelConfig
     */
    protected $modelConfig;
   
    /**
     * @param Context $context
     * @param ModelConfig $modelConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        ModelConfig $modelConfig,
        array $data = []
    ) {
        $this->modelConfig = $modelConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get config model
     *
     * @return ModelConfig
     */
    public function getConfig()
    {
        return $this->modelConfig;
    }

    /**
     * Get hide CSS class
     *
     * @return string
     */
    public function getHideCssClass()
    {
        $hidePageClass = '';
        $additionalPages = $this->getConfig()->getAdditionalPages();
        $currentPage = $this->getConfig()->getCurrentUrl();
        
        if ($this->getConfig()->isHideOnHome() && $this->isHomePage()) {
            $hidePageClass  = 'um-st-hide-home';
        } elseif ($this->getConfig()->isHideOnCms() && $this->isCmsPage()) {
            $hidePageClass  = 'um-st-hide-cms';
        } elseif ($this->getConfig()->isHideOnCategory() && $this->isCategoryPage()) {
            $hidePageClass  = 'um-st-hide-category';
        } elseif ($this->getConfig()->isHideOnProduct() && $this->isProductPage()) {
            $hidePageClass  = 'um-st-hide-product';
        } elseif ($this->getConfig()->isHideOnAdditional()
            && (strpos($additionalPages, $currentPage) !== false)) {
            $hidePageClass  = 'um-st-hide-additional';
        }
        
        return $hidePageClass;
    }

    /**
     * Check if current page is home page
     *
     * @return bool
     */
    public function isHomePage()
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        if ($fullActionName == 'cms_index_index') {
            return true;
        }
    }

    /**
     * Check if current page is cms page
     *
     * @return bool
     */
    public function isCmsPage()
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        if ($fullActionName == 'cms_page_view') {
            return true;
        }
    }

    /**
     * Check if current page is product page
     *
     * @return bool
     */
    public function isProductPage()
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        if ($fullActionName == 'catalog_product_view') {
            return true;
        }
    }

    /**
     * Check if current page is category page
     *
     * @return bool
     */
    public function isCategoryPage()
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        if ($fullActionName == 'catalog_category_view') {
            return true;
        }
    }
}
