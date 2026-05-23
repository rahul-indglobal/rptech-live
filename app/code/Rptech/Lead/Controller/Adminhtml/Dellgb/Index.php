<?php

namespace Rptech\Lead\Controller\Adminhtml\Dellgb;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_Lead::dell_gb_leads');
        $resultPage->getConfig()->getTitle()->prepend(__('Dell Pro Max With GB10 Leads'));
        return $resultPage;
    }
}
