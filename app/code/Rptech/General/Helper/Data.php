<?php
namespace Rptech\General\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const PRODUCT_LABEL_NEW_IMAGE = 'rpt_general/product_label/label_new';
    const PRODUCT_LABEL_NEW_STATUS = 'rpt_general/product_label/label_new_status';
    const PRODUCT_LABEL_SALE_IMAGE = 'rpt_general/product_label/label_sale';
    const PRODUCT_LABEL_SALE_STATUS = 'rpt_general/product_label/label_sale_status';
    const PRODUCT_LABEL_SOLD_IMAGE = 'rpt_general/product_label/label_sold';
    const PRODUCT_LABEL_SOLD_STATUS = 'rpt_general/product_label/label_sold_status';
    
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getLabelNewImage()
    {
        return $this->getConfig(self::PRODUCT_LABEL_NEW_IMAGE);
    }

    public function getLabelNewStatus()
    {
        return $this->getConfig(self::PRODUCT_LABEL_NEW_STATUS);
    }

    public function getLabelSaleImage()
    {
        return $this->getConfig(self::PRODUCT_LABEL_SALE_IMAGE);
    }

    public function getLabelSaleStatus()
    {
        return $this->getConfig(self::PRODUCT_LABEL_SALE_STATUS);
    }

    public function getLabelSoldImage()
    {
        return $this->getConfig(self::PRODUCT_LABEL_SOLD_IMAGE);
    }

    public function getLabelSoldStatus()
    {
        return $this->getConfig(self::PRODUCT_LABEL_SOLD_STATUS);
    }
}