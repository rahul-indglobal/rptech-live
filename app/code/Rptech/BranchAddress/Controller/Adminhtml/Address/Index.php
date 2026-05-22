<?php

namespace Rptech\BranchAddress\Controller\Adminhtml\Address;

use Magento\Framework\View\Result\Page;
use Rptech\BranchAddress\Controller\Adminhtml\AbstractBranchAddress;

/**
 * Class Index
 * @package Rptech\BranchAddress\Controller\Adminhtml\BranchAddress
 */
class Index extends AbstractBranchAddress
{
    /**
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_ServiceAddress::branch_addresses')
            ->addBreadcrumb(__('Branch Address'), __('Branch Address'))
            ->addBreadcrumb(__('Manage Branch Address'), __('Manage Branch Address'));
        $resultPage->getConfig()->getTitle()->prepend(
            __("Branch Address list")
        );
        return $resultPage;
    }
}
