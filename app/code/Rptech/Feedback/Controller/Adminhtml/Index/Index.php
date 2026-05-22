<?php

namespace Rptech\Feedback\Controller\Adminhtml\Index;

use Magento\Framework\View\Result\Page;
use Rptech\Feedback\Controller\Adminhtml\AbstractFeedback;

/**
 * Class Index
 * @package Rptech\Feedback\Controller\Adminhtml\Feedback
 */
class Index extends AbstractFeedback
{
    /**
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_Feedback::feedback')
            ->addBreadcrumb(__('Branch Address'), __('Feedback'))
            ->addBreadcrumb(__('Manage Feedback'), __('Manage Feedback'));
        $resultPage->getConfig()->getTitle()->prepend(
            __("Feedback list")
        );
        return $resultPage;
    }
}
