<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier\Order;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Session\SessionManager;
use Biztech\Inventorysystem\Model\Purchaseorders;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class Recent extends \Magento\Framework\View\Element\Template
{
    protected $_session;
    protected $_purchaseOrders;
    protected $_priceHelper;

    /**
     * @param Context        $context
     * @param SessionManager $session
     * @param Purchaseorders $purchaseOrders
     * @param PriceHelper    $priceHelper
     */
    public function __construct(
        Context $context,
        SessionManager $session,
        Purchaseorders $purchaseOrders,
        PriceHelper $priceHelper
    ) {
        $this->_session = $session;
        $this->_purchaseOrders = $purchaseOrders;
        $this->_priceHelper = $priceHelper;
        parent::__construct($context);
    }

    /**
     * supplier orders details
     * @return Object
     */
    public function getOrders()
    {
        return  $this->_purchaseOrders->getCollection()
                    ->addFieldToFilter('supplier_id', $this->_getSupplierData()->getSupplierId())
                    ->setOrder('id', 'DESC')
                    ->setPageSize(5);
    }

    /**
     * Supplier data
     * @return Object
     */
    protected function _getSupplierData()
    {
        return $this->_session->getSupplier();
    }

    /**
     * Pricing helper
     * @return Object
     */
    public function getPriceHelper()
    {
        return $this->_priceHelper;
    }
}
