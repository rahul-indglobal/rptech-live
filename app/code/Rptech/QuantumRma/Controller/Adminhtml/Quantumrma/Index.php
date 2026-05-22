<?php
namespace Rptech\QuantumRma\Controller\Adminhtml\Quantumrma;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
	const ADMIN_RESOURCE = 'Rptech_QuantumRma::quantumrma';

	protected $resultPageFactory;

	public function __construct(Action\Context $context, PageFactory $resultPageFactory)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Rptech_QuantumRma::quantumrma');
		$resultPage->getConfig()->getTitle()->prepend(__('Quantum Rma Submissions'));
		return $resultPage;
	}

	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Rptech_QuantumRma::quantumrma');
	}
}
