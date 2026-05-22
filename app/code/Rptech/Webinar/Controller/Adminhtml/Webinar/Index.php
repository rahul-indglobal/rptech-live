<?php
namespace Rptech\Webinar\Controller\Adminhtml\Webinar;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
	protected $resultPageFactory;

	public function __construct(Action\Context $context, PageFactory $resultPageFactory)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Rptech_Webinar::webinar');
		$resultPage->getConfig()->getTitle()->prepend(__('Webinar Submissions'));
		return $resultPage;
	}

	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Rptech_Webinar::webinar');
	}
}
