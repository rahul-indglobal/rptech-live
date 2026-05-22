<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\Stockreceived;

use Biztech\Inventorysystem\Controller\Adminhtml\Stockreceived\AbstractSR;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends AbstractSR
{

    /**
     * This function is used for view stock received
     * @return Object
     */
    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Biztech_Inventorysystem::stockreceived');
        $srID = $this->getRequest()->getParam('sr_incr_id');
        $sr = $this->getStockReceivedModel()->load($srID, 'stockreceived_id');

        $title = $sr->getStockreceivedId() . '|' . $this->_timezone->formatDate($sr->getCreatedAt(), \IntlDateFormatter::MEDIUM, true);
        $this->resultPage->getConfig()->getTitle()->prepend($title);
        return $this->resultPage;
    }
}
