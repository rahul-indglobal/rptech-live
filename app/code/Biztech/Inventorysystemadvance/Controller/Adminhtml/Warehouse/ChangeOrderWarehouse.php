<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

class ChangeOrderWarehouse extends \Magento\Backend\App\Action
{

    public $Request;
    protected $_warehouseProductModel;
    protected $_resourceConnection;
    protected $_orderItemModel;

    /**
     * @param \Magento\Backend\App\Action\Context                    $context
     * @param \Magento\Framework\App\Request\Http                    $Request
     * @param \Biztech\Inventorysystemadvance\Model\Warehouseproduct $warehouseProductModel
     * @param \Magento\Framework\App\ResourceConnection              $resourceConnection
     * @param \Magento\Sales\Model\Order\Item                        $orderItemModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $Request,
        \Biztech\Inventorysystemadvance\Model\Warehouseproduct $warehouseProductModel,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Sales\Model\Order\Item $orderItemModel
    ) {
        $this->Request = $Request;
        $this->_warehouseProductModel = $warehouseProductModel;
        $this->_resourceConnection = $resourceConnection;
        $this->_orderItemModel = $orderItemModel;
        parent::__construct($context);
    }

    /**
     * This function is used for change warehouse for order.
     * @return void
     */
    public function execute()
    {
        $postData = $this->Request->getParams();
        
        $warehouseProductModel = $this->_warehouseProductModel;

        $this->_resources = $this->_resourceConnection;
        $connection = $this->_resources->getConnection();
        $tableName = $this->_resources->getTableName('bc_warehouse_product_is');

        $orderItemData = $this->_orderItemModel->load($postData['item_id']);

        $productId = $postData['product_id'];
        $newWarehouseId = $postData['newWarehouse_id'];
        $curWarehouseId = $orderItemData->getWarehouseId();
        /* Add ordered qty to warehouse */
        $getWarehouseDetails = $connection->select()
                ->from($tableName, ['*'])
                ->where('warehouse_id = ' . $curWarehouseId . ' AND product_id = ' . $productId);
        $wareDetails = $connection->fetchAll($getWarehouseDetails);
        if (!empty($wareDetails[0])) {
            $qty = (int) $wareDetails[0]['quantity'] + (int) $postData['qty'];
            $warehouseProductModel->setId($wareDetails[0]['rel_id']);
        }
        $warehouseProductModel->setWarehouseId($curWarehouseId);
        $warehouseProductModel->setProductId($productId);
        $warehouseProductModel->setQuantity($qty);
        $warehouseProductModel->setPosition(0);
        $warehouseProductModel->save();

        /* Remove ordered qty to warehouse */
        $getWarehouseDetail = $connection->select()
                ->from($tableName, ['*'])
                ->where('warehouse_id = ' . $newWarehouseId . ' AND product_id = ' . $productId);
        $wareDetail = $connection->fetchAll($getWarehouseDetail);
        if (!empty($wareDetail[0])) {
            $qty = (int) $wareDetail[0]['quantity'] - (int) $postData['qty'];
            $warehouseProductModel->setId($wareDetail[0]['rel_id']);
        }
        $warehouseProductModel->setWarehouseId($newWarehouseId);
        $warehouseProductModel->setProductId($productId);
        $warehouseProductModel->setQuantity($qty);
        $warehouseProductModel->setPosition(0);
        $warehouseProductModel->save();

        /* change warehouse id in order item table */

        $orderItemData->setWarehouseId($newWarehouseId);
        $orderItemData->save();
    }
}
