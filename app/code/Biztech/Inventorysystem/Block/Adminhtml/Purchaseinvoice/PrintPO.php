<?php
/** 
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseinvoice;

use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Magento\Backend\Block\Widget\Context;

class PrintPO extends \Magento\Framework\View\Element\Template
{
    protected $_pricingHelper;
    protected $_currentStore;
    protected $_currencyInterface;
    protected $_orderModel;
    protected $_bizHelper;
    protected $_supplierModel;
    protected $_poInvoiceModel;
    protected $_poItemsModel;
    protected $_poInvoiceStatus;

    /**
     * @param Context                                               $context
     * @param BizHelper                                             $bizHelper
     * @param \Magento\Framework\Pricing\Helper\Data                $pricingHelper
     * @param \Magento\Store\Model\Store                            $currentStore
     * @param \Magento\Framework\Locale\CurrencyInterface           $currencyInterface
     * @param \Magento\Sales\Model\Order                            $orderModel
     * @param \Biztech\Inventorysystem\Model\Managesupplier         $supplierModel
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoice        $poInvoiceModel
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoiceitems   $poItemsModel
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoice\Status $poInvoiceStatus
     */
    public function __construct(
        Context $context,
        BizHelper $bizHelper,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Store\Model\Store $currentStore,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        \Magento\Sales\Model\Order $orderModel,
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel,
        \Biztech\Inventorysystem\Model\Purchaseinvoice $poInvoiceModel,
        \Biztech\Inventorysystem\Model\Purchaseinvoiceitems $poItemsModel,
        \Biztech\Inventorysystem\Model\Purchaseinvoice\Status $poInvoiceStatus
    ) {
    
        parent::__construct($context);
        $this->_bizHelper = $bizHelper;
        $this->_pricingHelper = $pricingHelper;
        $this->_currentStore = $currentStore;
        $this->_currencyInterface = $currencyInterface;
        $this->_orderModel = $orderModel;
        $this->_supplierModel = $supplierModel;
        $this->_poInvoiceModel = $poInvoiceModel;
        $this->_poItemsModel = $poItemsModel;
        $this->_poInvoiceStatus = $poInvoiceStatus;
    }

    /** 
     * Pricing helper
     * @return $this
     */
    public function getPriceingHelper(){
        return $this->_pricingHelper;
    }

    /** 
     * Current store
     * @return $this
     */
    public function getCurrentStore(){
        return $this->_currentStore;
    }

    /** 
     * Currency interface
     * @return $this
     */
    public function getCurrencyInterface(){
        return $this->_currencyInterface;
    }

    /** 
     * Biztech helper instance
     * @return $this
     */
    public function getBizHelper(){
        return $this->_bizHelper;
    }

    /**
     * order model
     * @return $this
     */
    public function getOrder(){
        return $this->_orderModel;
    }

    /**
     * Supplier model
     * @return $this
     */
    public function getSupplier(){
        return $this->_supplierModel;
    }

    /** 
     * PO invoice
     * @return object
     */
    public function getpoInvoice(){
        return $this->_poInvoiceModel;
    }

    /** 
     * PO items
     * @return $this
     */
    public function getPoItem(){
        return $this->_poItemsModel;
    }

    /** 
     * Invoice statuses
     * @return $this
     */
    public function getInoiceStatus(){
        return $this->_poInvoiceStatus;
    }
}
