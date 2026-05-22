<?php

namespace Rptech\CareerOpportunities\Controller\Adminhtml\Index;

use Magento\Framework\View\Result\Page;
use Rptech\CareerOpportunities\Controller\Adminhtml\AbstractCareerOpportunities;

/**
 * Class Index
 * @package Rptech\CareerOpportunities\Controller\Adminhtml\Index
 */
class Index extends AbstractCareerOpportunities
{
    /**
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_CareerOpportunities::career')
            ->addBreadcrumb(__('Career Opportunities'), __('Career Opportunities'))
            ->addBreadcrumb(__('Manage CareerOpportunities'), __('Manage CareerOpportunities'));
        $resultPage->getConfig()->getTitle()->prepend(
            __("Career Opportunities list")
        );
        return $resultPage;
    }
}
