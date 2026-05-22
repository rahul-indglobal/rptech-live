<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Framework\Event\Manager;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResourceConnection;

class UpdateStock extends \Magento\Backend\App\Action
{

    protected $_resource;
    protected $eventManager;
    protected $warehouseModel;
    protected $warehouseProductModel;

    /**
     * @param Context                                                $context
     * @param Manager                                                $eventManager
     * @param ResourceConnection                                     $resource
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse        $warehouseModel
     * @param \Biztech\Inventorysystemadvance\Model\Warehouseproduct $warehouseProductModel
     */
    public function __construct(
        Context $context,
        Manager $eventManager,
        ResourceConnection $resource,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        \Biztech\Inventorysystemadvance\Model\Warehouseproduct $warehouseProductModel
    ) {
        parent::__construct($context);
        $this->eventManager = $eventManager;
        $this->_resource = $resource;
        $this->warehouseModel = $warehouseModel;
        $this->warehouseProductModel = $warehouseProductModel;
    }

    /**
     * This function is used for update the stock from the barcode
     * @return void
     */
    public function execute()
    {

        $data = $this->getRequest()->getParams();
        if ($data) {
            try {
                $productID = $data['product_id'];
                /* save warehouse details */
                $connection = $this->_resource->getConnection('core_read');
                $tableName = $this->_resource->getTableName('bc_warehouse_product_is');
                $totalQtyRec = 0;
                foreach ($data['bar_scan_qty'] as $wareID => $qty) {
                    $totalQtyRec += (int) $qty;
                    $getWareDetails = $connection->select()
                            ->from($tableName, ['rel_id', 'quantity'])
                            ->where('product_id = ' . $productID . ' AND warehouse_id = ' . $wareID);
                    $getData = $connection->fetchAll($getWareDetails);

                    $wareProdModel = $this->warehouseProductModel;
                    if (!empty($getData[0])) {
                        $wareProdModel->setId($getData[0]['rel_id']);
                        $qtyBeforeUpd = (int) $getData[0]['quantity'];
                        $qty = (int) $getData[0]['quantity'] + (int) $qty;
                    } else {
                        $qtyBeforeUpd = 0;
                        $qty = (int) $qty;
                    }
                    $wareProdModel->setWarehouseId($wareID);
                    $wareProdModel->setProductId($productID);
                    $wareProdModel->setPosition(0);
                    $wareProdModel->setQuantity($qty);
                    $wareProdModel->save();

                    if ($qtyBeforeUpd != $qty) {
                        $warehouseName = $this->warehouseModel->load($wareID)->getWarehouseName();
                        $warehouseLog = ['warehouse_name' => $warehouseName,
                            'qty_before_upd' => $qtyBeforeUpd,
                            'qty_after_upd' => $qty,
                            'product_id' => $productID];
                        $this->eventManager->dispatch('warehouse_stock_update_log_barcode', $warehouseLog);
                    }
                }

                unset($data['bar_scan_qty']);
                $data['bar_scan_qty'] = $totalQtyRec;
                $this->eventManager->dispatch('product_stock_update_barcode_scan', $data);

                $this->messageManager->addSuccess(__('Stock Updated Successfully!'));
                $this->_redirect('*/*/index');
            } catch (\Exception $e) {
                $this->messageManager->addException($e);
            }
        }
        $this->_redirect('*/*/');
    }
}
