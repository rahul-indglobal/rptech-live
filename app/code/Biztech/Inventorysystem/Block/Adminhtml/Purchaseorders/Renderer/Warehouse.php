<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Biztech\Inventorysystemadvance\Model\Warehouse as WarehouseModel;

class Warehouse extends AbstractRenderer
{
    protected $_warehouse;

    /**
     * @param WarehouseModel $warehouse
     */
    public function __construct(
        WarehouseModel $warehouse
    ) {
        $this->_warehouse = $warehouse;
    }

    /**
     * Warehouse list
     * @param  DataObject $row
     * @return mixed
     */
    public function render(DataObject $row)
    {
        $warehouseHtml = '';
        if ($this->getColumn()->getIndex() == 'warehouse') {
            $productId = $row->getEntityId();
            $getData = $this->_warehouse->getWarehouseFromProduct($productId);
            $warehouseHtml = "<select class='admin__control-select required-entry select warehouse_select' id='warehouse_$productId' name='warehouse[$productId]'>";
            if (!empty($getData[0])) {
                for ($j = 0; $j < count($getData); $j++) {
                    $warehouseHtml .= "<option value='" . $getData[$j]['warehouse_id'] . "'>" . $this->_warehouse->load($getData[$j]['warehouse_id'])->getWarehouseName() . "</option>";
                }
            }
            $warehouseHtml .= "</select>";
        }
        return $warehouseHtml;
    }
}
