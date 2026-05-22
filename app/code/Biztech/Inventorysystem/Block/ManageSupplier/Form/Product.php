<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier\Form;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Session\SessionManager;
use Biztech\Inventorysystem\Model\Supplierproducttemp;

class Product extends \Biztech\Inventorysystem\Block\ManageSupplier\Dashboard
{
    protected $_session;
    protected $_supplierProducts;
    protected $_productModel;

    /**
     * @param Context                        $context
     * @param Supplierproducttemp            $supplierProducts
     * @param SessionManager                 $session
     * @param \Magento\Catalog\Model\Product $productModel
     */
    public function __construct(
        Context $context,
        Supplierproducttemp $supplierProducts,
        SessionManager $session,
        \Magento\Catalog\Model\Product $productModel
    ) {
        $this->_session = $session;
        $this->_supplierProducts= $supplierProducts;
        $this->_productModel = $productModel;
        parent::__construct($context);
    }

    /**
     * Supplier products
     * @return Object
     */
    public function getSupplierProduct()
    {
        $supplierProducts = $this->_supplierProducts->getCollection()->addFieldTofilter('supplier_id', $this->_getSupplierData()->getSupplierId());
        return $supplierProducts->getData();
    }
    
    /**
     * Supplier lastname
     * @return String
     */
    public function getSupplierLastName()
    {
        return $this->_getSupplierData()->getLastName();
    }

    /**
     * Supplier products
     * @return Object
     */
    public function getProduct()
    {
        return $this->_productModel;
    }
}
