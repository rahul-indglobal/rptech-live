<?php

namespace Rptech\StockUpdate\Block;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_adminSession;
    protected $_customerSession;
    protected $_stock;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Customer\Model\Session $customerSession,
        \Rptech\StockUpdate\Model\Stock $stock,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_adminSession = $authSession;
        $this->_customerSession = $customerSession;
        $this->_stock = $stock;
    }

    public function checkUserType()
    {
        if($this->_adminSession->isLoggedIn()) {
            //echo "Admin logged in";
            return "Admin";
        } elseif($this->_customerSession->isLoggedIn()) {
            //echo "Customer logged in";
            return "Customer";
        } else {
            return "Guest";
        }
    }

    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->getBaseUrl()."productstock/index/loginpost/";
    }

    public function getStockUpdateRecords()
    {
        $collection = $this->_stock->getCollection()->setOrder('row_id','desc')->setPageSize(10);
        return $collection;
    }
}