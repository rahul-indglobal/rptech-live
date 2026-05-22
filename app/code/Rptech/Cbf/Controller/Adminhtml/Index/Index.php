<?php
namespace Rptech\Cbf\Controller\Adminhtml\Index;

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
		$resultPage->setActiveMenu('Rptech_Cbf::cbfxv');
		$resultPage->getConfig()->getTitle()->prepend(__('CBFXV Leads'));
		return $resultPage;
	}

}
