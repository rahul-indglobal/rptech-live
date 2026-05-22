<?php

namespace Rptech\BranchAddress\Controller\Adminhtml\Address;

use Magento\Framework\View\Result\Page;
use Rptech\BranchAddress\Controller\Adminhtml\AbstractBranchAddress;

/**
 * Class Edit
 * @package Rptech\BranchAddress\Controller\Adminhtml\BranchAddress
 */
class Edit extends AbstractBranchAddress
{
    /**
     * @return Page
     */
    public function execute()
    {
        $dataId = $this->getRequest()->getParam('id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_ServiceAddress::branch_addresses')
            ->addBreadcrumb(__('Branch Address'), __('Branch Address'))
            ->addBreadcrumb(__('Manage Branch Address'), __('Manage Branch Address'));

        if ($dataId === null) {
            $resultPage->addBreadcrumb(__('New Branch Address'), __('New Branch Address'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Branch Address'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Branch Address'), __('Edit Branch Address'));
            $resultPage->getConfig()->getTitle()->prepend(
                $this->dataRepository->getById($dataId)->getBranchLocation()
            );
        }
        return $resultPage;
    }
}
