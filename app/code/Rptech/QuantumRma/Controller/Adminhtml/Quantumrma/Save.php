<?php
namespace Rptech\QuantumRma\Controller\Adminhtml\Quantumrma;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;

class Save extends Action
{
	const ADMIN_RESOURCE = 'Rptech_QuantumRma::quantumrma';

	public function execute()
	{
		/** @var Redirect $resultRedirect */
		$resultRedirect = $this->resultRedirectFactory->create();
		$data = $this->getRequest()->getPostValue();

		if ($data) {
			$id = $this->getRequest()->getParam('id');

			$model = $this->_objectManager->create(\Rptech\QuantumRma\Model\QuantumRma::class);

			if ($id) {
				$model->load($id);
				if (!$model->getId()) {
					$this->messageManager->addErrorMessage(__('This record no longer exists.'));
					return $resultRedirect->setPath('*/*/');
				}
			}

			$model->addData($data);

			try {
				$model->save();
				$this->messageManager->addSuccessMessage(__('You saved the record.'));
				return $resultRedirect->setPath('*/*/');
			} catch (\Exception $e) {
				$this->messageManager->addErrorMessage($e->getMessage());
			}
		}

		return $resultRedirect->setPath('*/*/');
	}
}
