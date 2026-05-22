<?php
namespace Magecomp\Mobilelogin\Block;

use Magecomp\Mobilelogin\Helper\Data;
use Magento\Framework\View\Element\Template\Context;

class Mobilelogin extends \Magento\Framework\View\Element\Template
{
    protected $_helper;

    public function __construct(Context $context, Data $helper)
    {
        $this->_helper = $helper;
        parent::__construct($context);
    }

    public function getLayoutType()
    {
        return $this->_helper->getLayoutType();
    }

    public function getOtpStringlenght()
    {
        return $this->_helper->getOtpStringlenght();
    }

    public function getTemplateImage()
    {
        return $this->_helper->getTemplateImage();
    }

    public function getImageType()
    {
        return $this->_helper->getImageType();
    }

    public function getMediaUrl()
    {
        $currentStore = $this->_storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }

    public function isEnable()
    {
        return $this->_helper->isEnable();
    }
}