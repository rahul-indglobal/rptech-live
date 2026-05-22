<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Catalog\Product\Edit\Tab;

class Content extends \Magento\Framework\View\Element\Template
{
    protected $scopeConfig;
    protected $inventorysystemadvanceMysql4Factory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse  $inventorysystemadvanceWarehouse
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Biztech\Inventorysystemadvance\Model\Warehouse $inventorysystemadvanceWarehouse,
        array $data = []
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->inventorysystemadvanceWarehouse = $inventorysystemadvanceWarehouse;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * This function is used for get the warehouse list
     * @param  int $warehouseID
     * @param  int $productID
     * @param  string $autoIncrID
     * @param  string $newRow
     * @return Array
     */
    protected function getWarehouseDropdown($warehouseID, $productID, $autoIncrID = '', $newRow = '')
    {
        
        if ($autoIncrID == '') {
            $html = '<select id="warehouse_code_{{index}}" name="product[bc_warehouse_product][{{index}}][warehouse_id]">';
        } else {
            $html = '<select id="warehouse_code_' . $autoIncrID . '" name="product[bc_warehouse_product][' . $autoIncrID . '][warehouse_id]">';
        }
        $defaultWarehouseID = $this->scopeConfig->getValue('inventorysystem/inventorysystemadvance/default_warehouse_select', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $getWarehouses = $this->inventorysystemadvanceWarehouse->create()->addFieldToFilter('status', 1)
            ->getData();
        if ($getWarehouses && is_array($getWarehouses) && !empty($getWarehouses)) {
            for ($i = 0; $i < count($getWarehouses); $i++) {
                if ($getWarehouses[$i]['id'] == $warehouseID && $newRow == '') {
                    $html .= '<option value="' . $getWarehouses[$i]['id'] . '" selected="selected">' . $getWarehouses[$i]['warehouse_name'] . '</option>';
                } else {
                    $html .= '<option value="' . $getWarehouses[$i]['id'] . '">' . $getWarehouses[$i]['warehouse_name'] . '</option>';
                }
            }
        } else {
            $html .= '<option value="0">No Warehouse Created</option>';
        }
        $html .= '</select>';
        return $html;
    }
}
