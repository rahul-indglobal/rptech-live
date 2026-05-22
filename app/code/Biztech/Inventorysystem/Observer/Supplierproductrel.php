<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Observer;

use Biztech\Inventorysystem\Model\ResourceModel\Managesupplier;
use Biztech\Inventorysystem\Model\Managesupplierproduct;
use Biztech\Inventorysystem\Model\ResourceModel\Supplierproducttemp as ResourceSupplierproducttemp;
use Biztech\Inventorysystem\Model\Supplierproducttemp;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Supplierproductrel implements ObserverInterface
{

    protected $_supplierProducts;
    protected $_supplierTempProducts;
    protected $_resources;

    /**
     * @param Managesupplierproduct $supplierProducts
     * @param Supplierproducttemp   $supplierTempProducts
     * @param ResourceConnection    $resourceConnection
     */
    function __construct(
        Managesupplierproduct $supplierProducts,
        Supplierproducttemp $supplierTempProducts,
        ResourceConnection $resourceConnection
    ) {
        $this->_supplierTempProducts = $supplierTempProducts;
        $this->_supplierProducts = $supplierProducts;
        $this->_resources = $resourceConnection;
    }

    /**
     * This function is used for the manage the supplier product relation
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $connection = $this->_resources->getConnection();
        $supplierProductTable = $connection->getTableName($this->_resources->getTableName(Managesupplier::TBL_ATT_PRODUCT));
        $supplierTempProductTable = $connection->getTableName($this->_resources->getTableName(ResourceSupplierproducttemp::TBL_SUP_PRODUCT_APPROVE));
        
        $product = $observer->getProduct();
        $productId = $product->getEntityId();
        $supIds = '';

        if ($product->getBcSupplierIs()) {
            $supIds = explode(',', $product->getBcSupplierIs());
        }

        if (is_array($supIds) && !empty($supIds)) {
            $existingSupplier = (array) $this->_supplierProducts->getSuppliers($productId, 'supplier_id');

            $insert = array_diff($supIds, $existingSupplier);
            $delete = array_diff($existingSupplier, $supIds);

            if ($delete) {
                foreach ($delete as $supplier_id) {
                    $fields['supplier_status'] = 2;
                    $where = ['supplier_id = ?' => (int) $supplier_id, 'product_id = ?' => $productId];
                    $connection->delete($supplierProductTable, $where);
                    $connection->update($supplierTempProductTable, $fields, $where);
                }
            }

            if ($insert) {
                $data = [];

                foreach ($insert as $supplier_id) {
                    $data[] = ['supplier_id' => (int) $supplier_id, 'product_id' => (int) $productId];
                    $fields['supplier_status'] = 1;

                    $where = ['supplier_id = ?' => (int) $supplier_id, 'product_id = ?' => $productId];
                    $connection->update($supplierTempProductTable, $fields, $where);
                }
                // $connection->insertMultiple($supplierProductTable, $data);
            }

            if ($supIds) {
                foreach ($supIds as $supId) {
                    $approveArray = $this->_supplierTempProducts->getSupplierProduct($supId, $productId);

                    if (is_array($approveArray) && empty($approveArray)) {
                        $supProdTempModel = $this->_supplierTempProducts;
                        $supProdTempModel->setSupplierId($supId)
                                ->setProductId($productId)
                                ->setApproveStatus('approved')
                                ->setNewProdFlag(0)
                                ->setSupplierStatus(1)
                                ->save();
                    }
                }
            }
        }
        return $this;
    }
}
