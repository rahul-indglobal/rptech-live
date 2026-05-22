<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Biztech\Inventorysystem\Helper\Data;

class SaveInvoice extends Action
{

    protected $_inventorysystemHelper;
    protected $resultPageFactory;
    protected $resultPage;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     * @param Data        $inventorysystemHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $inventorysystemHelper
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_inventorysystemHelper = $inventorysystemHelper;
    }

    /**
     * This function is used for save invoice
     * @return Void
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            try {
                $this->_inventorysystemHelper->savePurchaseInvoice($data);
                $this->_redirect('inventorysystem/purchaseinvoice/index');
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('inventorysystem/purchaseinvoice/index');
            }
        }
    }
}
