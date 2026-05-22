<?php

namespace Rptech\Cbf\Controller\Form;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Rptech\Cbf\Model\CbfxvLeadFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Submit extends Action
{
	protected $resultJsonFactory;
	protected $leadFactory;
	protected $date;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		JsonFactory $resultJsonFactory,
		CbfxvLeadFactory $leadFactory,
		DateTime $date
	) {
		$this->resultJsonFactory = $resultJsonFactory;
		$this->leadFactory = $leadFactory;
		$this->date = $date;
		parent::__construct($context);
	}

	public function execute()
	{
		$post = $this->getRequest()->getParams();
		$resultRedirect = $this->resultRedirectFactory->create();

		try {
			$leadmodel = $this->leadFactory->create();
			$leadmodel->setData($post);
			$leadmodel->save();

			// Add success message
			$this->messageManager->addSuccessMessage(__('Your lead has been submitted successfully.'));
		} catch (\Exception $e) {
			// Add error message
			$this->messageManager->addErrorMessage(__('There was an error submitting your lead. Please try again.'));
		}

		// Redirect to /cbfxv
		return $resultRedirect->setPath('cbfxv');
	}
}
