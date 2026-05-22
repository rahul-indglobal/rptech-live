<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Renderer;

class Renderer extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_supplierModel;
    protected $_resourceConnection;
    protected $_barcodeStatus;
    protected $_purchaseOrderModel;

    /**
     * @param \Biztech\Inventorysystem\Model\Managesupplier        $supplierModel
     * @param \Magento\Framework\App\ResourceConnection            $resourceConnection
     * @param \Biztech\Inventorysystemadvance\Model\Barcode\Status $barcodeStatus
     * @param \Biztech\Inventorysystem\Model\Purchaseorders        $purchaseOrderModel
     */
    public function __construct(
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Biztech\Inventorysystemadvance\Model\Barcode\Status $barcodeStatus,
        \Biztech\Inventorysystem\Model\Purchaseorders $purchaseOrderModel
    ) {

        $this->_supplierModel = $supplierModel;
        $this->_resourceConnection = $resourceConnection;
        $this->_barcodeStatus = $barcodeStatus;
        $this->_purchaseOrderModel = $purchaseOrderModel;
    }

    /**
     * This function is used for showing the warehouse and supplier details on barcode page
     * @param  \Magento\Framework\DataObject $row
     * @return mixed
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        
        $txtbox = "";

        if ($this->getColumn()->getIndex() == 'sku') {
            $txtbox .= "<span>" . $row->getSku() . "</span><input type='hidden' disabled='disabled' id='prod_sku_" . $row->getEntityId() . "' name='prod_sku[" . $row->getEntityId() . "]' value='" . $row->getSku() . "' />";
        }
        if ($this->getColumn()->getIndex() == 'barcode') {
            $txtbox .= "<label>Auto generate: </label><input type='checkbox' checked='checked' id='auto_generate_" . $row->getEntityId() . "' name='auto_generate[" . $row->getEntityId() . "]' value='" . $row->getEntityId() . "' class='autoGenChkbx' disabled='disabled' /><br><input type='text' id='ud_barcode_" . $row->getEntityId() . "' name='ud_barcode[" . $row->getEntityId() . "]' width='95%' disabled='disabled' />";
        }
        if ($this->getColumn()->getIndex() == 'qty') {
            $txtbox .= "<input type='text' disabled='disabled' id='barcode_qty_" . $row->getEntityId() . "' name='barcode_qty[" . $row->getEntityId() . "]' size='5' />";
        }
        if ($this->getColumn()->getIndex() == 'supplier') {
            $txtbox .= "<select item_id='" . $row->getEntityId() . "' class='supplierSel' id='supplier_" . $row->getEntityId() . "' name='supplier[" . $row->getEntityId() . "]' disabled='disabled'>";
            $txtbox .= "<option value=''>--Select Supplier--</option>";
            $supplierArray = $this->_supplierModel->getAllOptions();
            for ($i = 0; $i < count($supplierArray); $i++) {
                $txtbox .= "<option value='" . $supplierArray[$i]['value'] . "'>" . $supplierArray[$i]['label'] . "</option>";
            }
            $txtbox .= "</select>";
        }
        if ($this->getColumn()->getIndex() == 'purchase_order') {
            $this->_resources = $this->_resourceConnection;
            $connection = $this->_resources->getConnection();

            $collection = $this->_purchaseOrderModel->getCollection()
                    ->addFieldToSelect(['id', 'purchase_order_id']);
            $poArray = $collection->getSelect()
                    ->joinLeft(['poi' => $this->_resources->getTableName('bc_purchaseorder_items_is')], 'main_table.id=poi.purchase_order_id', ['product_id'])
                    ->where('poi.product_id=' . $row->getEntityId());
            $result = $connection->fetchAll($poArray);
            if (count($result) > 0) {
                $txtbox .= "<select id='purchase_order_" . $row->getEntityId() . "' name='purchase_order[" . $row->getEntityId() . "]' disabled='disabled'>";
                $txtbox .= "<option value=''>--Select PurchaseOrder--</option>";
                for ($i = 0; $i < count($result); $i++) {
                    $txtbox .= "<option value='" . $result[$i]['id'] . "'>" . $result[$i]['purchase_order_id'] . "</option>";
                }
                $txtbox .= "</select>";
            } else {
                //$txtbox .= "No Purchase Order";
                $txtbox .= "No Purchase Order <input type='hidden' disabled='disabled' id='purchase_order_" . $row->getEntityId() . "' name='purchase_order[" . $row->getEntityId() . "]' value='0' />";
            }
        }
        if ($this->getColumn()->getIndex() == 'barcode_status') {
            $txtbox .= "<select id='status_" . $row->getEntityId() . "' name='status[" . $row->getEntityId() . "]' disabled='disabled'>";
            $getStatuses = $this->_barcodeStatus->toOptionArray();
            foreach ($getStatuses as $key => $value) {
                $txtbox .= "<option value='" . $key . "'>" . $value->getText() . "</option>";
            }
            $txtbox .= "</select>";
        }
        return $txtbox;
    }
}
