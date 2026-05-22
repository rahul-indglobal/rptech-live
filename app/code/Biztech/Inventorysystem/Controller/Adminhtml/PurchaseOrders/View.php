<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Biztech\Inventorysystem\Model\Purchaseorders;
use Magento\Framework\Stdlib\DateTime\Timezone;

class View extends Action
{
    protected $resultPageFactory;
    protected $resultPage;
    protected $_purchaseOrders;
    protected $_timezone;

    /**
     * @param Context        $context
     * @param PageFactory    $resultPageFactory
     * @param Purchaseorders $purchaseOrders
     * @param Timezone       $timezone
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Purchaseorders $purchaseOrders,
        Timezone $timezone
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_purchaseOrders = $purchaseOrders;
        $this->_timezone = $timezone;
    }

    /**
     * This function is used for view PO
     * @return Object
     */
    public function execute()
    {

        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Biztech_Inventorysystem::purchaseorders');
        $poID = $this->getRequest()->getParam('id');
        $po = $this->_purchaseOrders->load($poID);
        $title = $po->getPurchaseOrderId() . '|' . $this->_timezone->formatDate($po->getCreatedAt(), \IntlDateFormatter::MEDIUM, true);
        $this->resultPage->getConfig()->getTitle()->prepend($title);
        return $this->resultPage;
    }
}
