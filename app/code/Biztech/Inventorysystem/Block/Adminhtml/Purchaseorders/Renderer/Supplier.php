<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Biztech\Inventorysystem\Model\PurchaseOrders\Supplier as PurchaseOrderSupplier;
use Biztech\Inventorysystem\Helper\Data as BizHelper;

class Supplier extends AbstractRenderer
{

    protected $_supplier;
    protected $_bizHelper;

    /**
     * @param PurchaseOrderSupplier $supplier
     * @param BizHelper             $bizHelper
     */
    public function __construct(
        PurchaseOrderSupplier $supplier,
        BizHelper $bizHelper
    ) {
        $this->_supplier = $supplier;
        $this->_bizHelper = $bizHelper;
    }

    /**
     * Supplier details
     * @param  DataObject $row
     * @return Object
     */
    public function render(DataObject $row)
    {
        $txtbox = '';
        $productId = $row->getEntityId();
        if ($this->getColumn()->getIndex() == 'supplier_id') {
            // $supplierData =
        } else if ($this->getColumn()->getIndex() == 'supplier') { /* condition to get supplier for po create product grid */
            $supplierData = $this->_supplier->getSupplierData();
            if (!is_null($row->getBcSupplierIs())) {
                $txtbox .= $this->_bizHelper->getSupplierName($supplierData, $row->getBcSupplierIs(), null, $productId);
            } else {
                $txtbox .= $this->_bizHelper->getSupplierName($supplierData);
            }
        }

        return $txtbox;
    }
}
