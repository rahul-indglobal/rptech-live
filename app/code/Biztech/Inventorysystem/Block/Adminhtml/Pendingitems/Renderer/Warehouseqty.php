<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Pendingitems\Renderer;

use Biztech\Inventorysystemadvance\Model\Warehouse;
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;

class Warehouseqty extends AbstractRenderer
{
    protected $_resource;
    protected $_warehouseModel;

    /**
     * @param Context            $context
     * @param Warehouse          $warehouseModel
     * @param ResourceConnection $resource
     */
    public function __construct(
        Context $context,
        Warehouse $warehouseModel,
        ResourceConnection $resource
    ) {
        parent::__construct($context);
        $this->_resource = $resource;
        $this->_warehouseModel = $warehouseModel;
    }

    /**
     * This function is used for show warehouse qty for the pending items
     * @param  DataObject $row
     * @return String
     */
    public function render(DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'warehouse_qty') {
            $connection = $this->_resource->getConnection();
            $tableName = $this->_resource->getTableName('bc_warehouse_product_is');

            $getIncrIds = $connection->select()
                ->from($tableName, array('warehouse_id', 'quantity'))
                ->where('product_id = ' . $row->getId());
            $getData = $connection->fetchAll($getIncrIds);

            if (!empty($getData[0])) {
                if (!$this->getRequest()->getParam('inventorysystem') && !$this->getRequest()->getParam('demo_csv')) {
                    for ($i = 0; $i < count($getData); $i++) {
                        $txtbox .= '<a title="'. $this->_warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() .'" target="_blank" href="' . $this->getUrl('inventorysystemadvance/warehouse/edit', ['id' => $getData[$i]['warehouse_id']]) . '">' . $this->_warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . '</a> : ' . $getData[$i]['quantity'];
                        $txtbox .= '<br/>';
                    }
                } else {
                    for ($i = 0; $i < count($getData); $i++) {
                        if ($i == count($getData) - 1) {
                            $txtbox .= $this->_warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . ":" . $getData[$i]['quantity'];
                        } else {
                            $txtbox .= $this->_warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . ":" . $getData[$i]['quantity'] . ";";
                        }
                    }
                }
            } else {
                $txtbox .= '';
            }
        }

        return $txtbox;
    }
}
