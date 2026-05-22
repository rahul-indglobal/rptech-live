<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier\Form;

use Magento\Framework\View\Element\Template\Context;
use Biztech\Inventorysystem\Model\Purchaseorders;

class Viewpo extends \Magento\Framework\View\Element\Template
{

    protected $_gridFactory;
    protected $_poModel;
    protected $_poitemsModel;
    protected $_pocommentsModel;
    protected $_resourceConnection;
    protected $_supplierModel;
    protected $_storeManager;
    protected $_priceCurrency;
    protected $_orderModel;

    /**
     * @param Context                                              $context
     * @param Purchaseorders                                       $productFactory
     * @param \Biztech\Inventorysystem\Model\Purchaseorders        $poModel
     * @param \Biztech\Inventorysystem\Model\Purchaseordersitems   $poitemsModel
     * @param \Biztech\Inventorysystem\Model\Purchaseordercomments $pocommentsModel
     * @param \Magento\Framework\App\ResourceConnection            $resourceConnection
     * @param \Biztech\Inventorysystem\Model\Managesupplier        $supplierModel
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface    $priceCurrency
     * @param \Magento\Sales\Model\Order                           $orderModel
     */
    public function __construct(
        Context $context,
        Purchaseorders $productFactory,
        \Biztech\Inventorysystem\Model\Purchaseorders $poModel,
        \Biztech\Inventorysystem\Model\Purchaseordersitems $poitemsModel,
        \Biztech\Inventorysystem\Model\Purchaseordercomments $pocommentsModel,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Sales\Model\Order $orderModel
    ) {
        parent::__construct($context);
        $this->_productFactory = $productFactory;
        $this->session = $context->getSession();
        $collection = $productFactory->getCollection()
                ->addFieldToFilter('supplier_id', $this->_getSupplierData()->getSupplierId())
                ->setOrder('id', 'DESC');

        $this->setCollection($collection);
        $this->_poModel = $poModel;
        $this->_poitemsModel = $poitemsModel;
        $this->_pocommentsModel = $pocommentsModel;
        $this->_resourceConnection = $resourceConnection;
        $this->_supplierModel = $supplierModel;
        $this->_storeManager = $context->getStoreManager();
        $this->_priceCurrency = $priceCurrency;
        $this->_orderModel = $orderModel;
    }

    /**
     * Supplier product collection
     * @return Object
     */
    public function getProductCollection()
    {
        return $this->getCollection();
    }

    /**
     * Supplier data
     * @return Object
     */
    protected function _getSupplierData()
    {
        return $this->session->getSupplier();
    }

    /**
     * Limit
     * @return Array
     */
    public function getAvailableLimit()
    {
        return array(1 => 1, 2 => 2, 3 => 3);
    }

    /**
     * Prepare layout
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'Magento\Theme\Block\Html\Pager',
            'fme.news.pager'
        )->setAvailableLimit(array(5 => 5, 10 => 10, 15 => 15))->setShowPerPage(true)->setCollection(
            $this->getProductCollection()
        );
        $this->setChild('pager', $pager);
        return $this;
    }

    /**
     * Pager html
     * @return $this
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Supplier PO
     * @return Object
     */
    public function getPO()
    {
        return $this->_poModel;
    }

    /**
     * Supplier PO item
     * @return Object
     */
    public function getPOItem()
    {
        return $this->_poitemsModel;
    }

    /**
     * Supplier PO comment
     * @return Object
     */
    public function getPOComment()
    {
        return $this->_pocommentsModel;
    }

    /**
     * Resource connection
     * @return Object
     */
    public function getResourceConnection()
    {
        return $this->_resourceConnection;
    }

    /**
     * Supplier data
     * @return Object
     */
    public function getSupplier()
    {
        return $this->_supplierModel;
    }

    /**
     * Store details
     * @return Object
     */
    public function getStore()
    {
        return $this->_storeManager;
    }

    /**
     * Price currency
     * @return Object
     */
    public function getPriceCurrency()
    {
        return $this->_priceCurrency;
    }

    /**
     * Supplier model
     * @return Object
     */
    public function getOrder()
    {
        return $this->_orderModel;
    }
}
