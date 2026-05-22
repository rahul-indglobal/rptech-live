<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Magento\Framework\Event\ObserverInterface;

class SaveStockReceived extends Action
{
    protected $resultPageFactory;
    private $eventManager;
    protected $resultPage;
    protected $_bizHelper;

    /**
     * @param Context                          $context
     * @param PageFactory                      $resultPageFactory
     * @param BizHelper                        $bizHelper
     * @param \Magento\Framework\Event\Manager $eventManager
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        BizHelper $bizHelper,
        \Magento\Framework\Event\Manager $eventManager
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_bizHelper = $bizHelper;
        $this->eventManager = $eventManager;
    }

    /**
     * This function is used for save stock received
     * @return Void
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getPost()) {
            try {
                $result = $this->_bizHelper->saveStockReceived($data);
                if ($result == "Not inserted successfully!") {
                    $this->_redirect('*/*/');
                } else {
                    $eventData = $this->getRequest()->getParams();
                    $this->eventManager->dispatch('update_product_stock_after_stock_received', $eventData);
                    $this->_redirect('*/stockreceived/index');
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
        }
    }
}
