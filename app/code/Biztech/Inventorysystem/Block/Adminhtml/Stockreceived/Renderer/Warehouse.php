<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Stockreceived\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Backend\Block\Context;
use Biztech\Inventorysystemadvance\Model\Warehouse as WarehouseModel;
use Biztech\Inventorysystem\Helper\Data;

class Warehouse extends AbstractRenderer
{
    protected $_warehouseModel;
    protected $_bizHelper;

    /**
     * @param Context        $context
     * @param WarehouseModel $warehouseModel
     * @param Data           $helper
     */
    public function __construct(
        Context $context,
        WarehouseModel $warehouseModel,
        Data $helper
    ) {
        parent::__construct($context);
        $this->_warehouseModel = $warehouseModel;
        $this->_bizHelper = $helper;
    }

    /**
     * Warehouse name for the stock received
     * @param  DataObject $row
     * @return String
     */
    public function render(DataObject $row)
    {
        $warehouseName = '';

        if ($this->getColumn()->getIndex() == 'warehouse_id') {
            $wm = $this->_warehouseModel->load($row->getWarehouseId());
            if (is_null($row->getWarehouseId())) {
                $defaultWarehouseID = $this->_bizHelper->getConfig('inventorysystem/inventorysystemadvance/default_warehouse_select');
            }

            if (empty($wm->getData())) {
                $defaultWarehouseID = $this->_bizHelper->getConfig('inventorysystem/inventorysystemadvance/default_warehouse_select');
                $wm = $this->_warehouseModel->load($defaultWarehouseID);
                $warehouseName = $wm->getWarehouseName();
            } else {
                $warehouseName = $wm->getWarehouseName();
            }
        }
        return $warehouseName;
    }
}
