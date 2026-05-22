<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Managesupplier extends AbstractDb
{
    const TBL_ATT_PRODUCT = 'bc_supplier_product_is';
    const TBL_ATT_PRODUCT_APPROVE = 'bc_supplier_product_approve_is';
    protected $_date;
    protected $_productInstance = null;
    protected $_supplierProduct;
    /**
     * Construct
     *
     * @param Context $context
     * @param DateTime $date
     * @param string|null $resourcePrefix
     */
    public function __construct(
        Context $context,
        DateTime $date,
        \Biztech\Inventorysystem\Model\Managesupplierproduct $supplierProduct,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
        $this->_supplierProduct = $supplierProduct;
    }

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('bc_supplier_is', 'supplier_id');
    }

    /**
     * Selected products of supplier
     * @return object
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Selected procucts collection for supplier
     * @return object
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * Product instance
     * @return object
     */
    protected function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = $this->_supplierProduct;
        }
        return $this->_productInstance;
    }

    /**
     * Save supplier
     * @param  \Magento\Framework\Model\AbstractModel $object
     * @return object
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreatedAt($this->_date->gmtDate());
        }

        $object->setUpdatedAt($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }
}
