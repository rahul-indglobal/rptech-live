<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem;

class Warehouseselector extends \Magento\Backend\Block\Template
{

    public $scopeConfig;
    protected $_warhouseModel;

    /**
     * @param \Magento\Backend\Block\Template\Context         $context
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warhouseModel
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warhouseModel
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->_warhouseModel = $warhouseModel;
        parent::__construct($context);
    }
    /**
     * show or not
     * @return boolean
     */
    public function isShow()
    {
        return 1;
    }

    /**
     * Switch url for warehouse
     * @return String
     */
    protected function getSwitchUrl()
    {
        return $this->getUrl('*/*/index');
    }

    /**
     * Warehouse options
     * @return Array
     */
    public function getWarehouseOption()
    {

        return $this->_warhouseModel->getAllOptions();
    }
}
