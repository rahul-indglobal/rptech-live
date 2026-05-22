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

class Sendemail extends Action
{
    protected $resultPageFactory;
    protected $_bizhelper;
    protected $resultPage;

    /**
     * @param Context     $context
     * @param BizHelper   $bizHelper
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        BizHelper $bizHelper,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_bizhelper = $bizHelper;
    }

    /**
     * This function is used for send email for PO
     * @return Void
     */
    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $this->_bizhelper->sendEmail(88);
        return;
    }
}
