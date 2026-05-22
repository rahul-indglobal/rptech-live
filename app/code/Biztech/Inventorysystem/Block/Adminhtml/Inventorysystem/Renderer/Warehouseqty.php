<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Inventorysystem\Renderer;

class Warehouseqty extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $request;
    protected $_warehouseModel;
    protected $_resourceConnection;
    protected $_backendUrl;

    /**
     * @param \Magento\Framework\App\Request\Http             $request
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     * @param \Magento\Framework\App\ResourceConnection       $resourceConnection
     * @param \Magento\Backend\Model\UrlInterface             $backendUrl
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        $this->request = $request;
        $this->_warehouseModel = $warehouseModel;
        $this->_resourceConnection = $resourceConnection;
        $this->_backendUrl = $backendUrl;
    }

    /**
     * Manage stock warehouse qty
     * @param  \Magento\Framework\DataObject $row
     * @return String
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        if ($this->getColumn()->getIndex() == 'warehouse_qty') {
            $txtbox = '';
            $this->_resources = $this->_resourceConnection;
            $connection = $this->_resources->getConnection();
            $tableName = $this->_resources->getTableName('bc_warehouse_product_is');
            $getIncrIds = $connection->select()
                ->from($tableName, array('warehouse_id', 'quantity'))
                ->where('product_id = ' . $row->getId());
            $getData = $connection->fetchAll($getIncrIds);
            if (!empty($getData[0])) {
                $warehouseModel = $this->_warehouseModel;
                $backendUrl = $this->_backendUrl;
                if (!$this->request->getParam('inventorysystem') && !$this->request->getParam('demo_csv')) {
                    for ($i = 0; $i < count($getData); $i++) {
                        $txtbox .= "<a target='_blank' href='" . $backendUrl->getUrl('inventorysystemadvance/warehouse/edit', array('id' => $getData[$i]['warehouse_id'])) . "'>" . $warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . "</a>: " . $getData[$i]['quantity'];
                        $txtbox .= "<br />";
                    }
                } else {
                    for ($i = 0; $i < count($getData); $i++) {
                        if ($i == count($getData) - 1) {
                            $txtbox .= $warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . ":" . $getData[$i]['quantity'];
                        } else {
                            $txtbox .= $warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . ":" . $getData[$i]['quantity'] . ";";
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
