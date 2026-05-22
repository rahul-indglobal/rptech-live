<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\PurchaseOrders;

use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Biztech\Inventorysystem\Model\ManagesupplierFactory;
use Magento\Framework\Option\ArrayInterface;

class Supplier implements ArrayInterface
{
    protected $_bizHelper;
    protected $_supplierFactory;

    /**
     * @param BizHelper             $bizHelper
     * @param ManagesupplierFactory $supplierFactory
     */
    public function __construct(
        BizHelper $bizHelper,
        ManagesupplierFactory $supplierFactory
    ) {
        $this->_bizHelper = $bizHelper;
        $this->_supplierFactory = $supplierFactory;
    }

    /**
     * This function is used for get the supplier details
     * @param  int $product_id
     * @return Array
     */
    public function toOptionArray($product_id = null)
    {
        $options = [];
        $connection = $this->_bizHelper->getResource()->getConnection();

        $defaultSupplier = $this->_bizHelper->getConfig('inventorysystem/supplierconfig/showsupplier');
        $supplierModel = $this->_supplierFactory->create();
        $supplierCollection = $supplierModel->getCollection()
            ->addFieldToFilter('is_active', 1);

        if ($defaultSupplier == 0) {
            $supplierCollection->addFieldToFilter('product_id', $product_id);
            $supplierCollection->getSelect()->join(
                ['supplier_product' => $this->_bizHelper->getResource()->getTableName('bc_supplier_product_is')],
                'supplier_product.supplier_id = main_table.supplier_id',
                ['supplier_product.product_id']
            );

            if ($supplierCollection->count() == 0) {
                $supplierModel = $this->_supplierFactory->create();
                $supplierCollection = $supplierModel->getCollection()
                    ->addFieldToFilter('is_active', 1);
            }
        }
        $supplierData = $supplierCollection->getData();
        foreach ($supplierData as $key => $value) {
            $options[$value['supplier_id']] = $value['first_name'] . ' ' . $value['last_name'];
        }
        return $options;
    }

    public function getSupplierData()
    {
        $supplierCollection = $this->_supplierFactory->create()->getCollection()
            ->addFieldToFilter('is_active', 1)
            ->setOrder('first_name', 'ASC');
        $supplierData = $supplierCollection->getData();
        return $supplierData;
    }
}
