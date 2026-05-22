<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier\Form;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Session\SessionManager;
use Biztech\Inventorysystem\Model\Supplierproducttemp;

class Createproduct extends \Biztech\Inventorysystem\Block\ManageSupplier\Dashboard
{
    protected $_session;
    protected $_supplierProducts;
    protected $_productTaxSource;

    /**
     * @param Context                                    $context
     * @param Supplierproducttemp                        $supplierProducts
     * @param SessionManager                             $session
     * @param \Magento\Tax\Model\TaxClass\Source\Product $productTaxSource
     */
    public function __construct(
        Context $context,
        Supplierproducttemp $supplierProducts,
        SessionManager $session,
        \Magento\Tax\Model\TaxClass\Source\Product $productTaxSource
    ) {
        $this->_session = $session;
        $this->_supplierProducts= $supplierProducts;
        $this->_productTaxSource = $productTaxSource;
        parent::__construct($context);
    }

    /**
     * Supplier products
     * @return Object
     */
    protected function getSupplierProduct()
    {
             
        $supplierProducts = $this->_supplierProduct->getCollection()->addFieldTofilter('supplier_id', $this->_getSupplierData()->getSupplierId());
        return $supplierProducts->getData();
    }

    /**
     * Supplier lastname
     * @return string
     */
    public function getSupplierLastName()
    {
        return $this->_getSupplierData()->getLastName();
    }

    /**
     * Product tex source
     * @return Object
     */
    public function getProductTaxSource()
    {
        return $this->_productTaxSource;
    }
}
