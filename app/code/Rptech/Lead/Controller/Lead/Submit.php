<?php

namespace Rptech\Lead\Controller\Lead;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Rptech\Lead\Model\DellLeadFactory;
use Rptech\Lead\Model\DellLead;
use Rptech\Lead\Model\FormValidator;

class Submit extends Action
{
	protected DellLeadFactory $dellLeadFactory;
	protected FormValidator $formValidator;

	public function __construct(
		Context $context,
		DellLeadFactory $dellLeadFactory,
		FormValidator $formValidator
	)
	{
		$this->dellLeadFactory = $dellLeadFactory;
		$this->formValidator = $formValidator;
		parent::__construct($context);
	}

	public function execute()
	{
		// Fetch post data
		$post = $this->getRequest()->getPostValue();

		// If no data (direct URL hit)
		if (!$post) {
			$this->messageManager->addErrorMessage(__('No form data received.'));
			$_SESSION['error_msg'] = 'No form data received.';
			return $this->_redirect($this->_redirect->getRefererUrl());
		}

		try {
			// Validate Input (Backend Validation)
			$this->formValidator->validate($post);

			// Determine redirect URL safely
			$url = (!empty($post['type']) && $post['type'] == "Dell Form")
				? 'dell-form'
				: 'dellstoragethroughpartner';

			/** @var DellLead $model */
			$model = $this->dellLeadFactory->create();

			// NEVER save raw $post without filtering allowed fields
			$allowedFields = [
				'first_name', 'last_name', 'email', 'phone', 'company', 'type'
			];

			$cleanData = array_intersect_key($post, array_flip($allowedFields));
			$model->setData($cleanData);

			// Save inside try/catch for better error control
			$model->save();

			$this->messageManager->addSuccessMessage(__('Lead saved successfully.'));
			$_SESSION['success_msg'] = 'Lead saved successfully.';
			if ($post['type'] == "Dell Form") {
				$_SESSION['display_links'] = 'yes';
			}


		} catch (\Magento\Framework\Exception\InputException $e) {

			// Validation failure message
			$this->messageManager->addErrorMessage($e->getMessage());
			$_SESSION['error_msg'] = $e->getMessage();
			return $this->_redirect($this->_redirect->getRefererUrl());

		} catch (\Exception $e) {

			// Log the actual error
			$this->logger->critical($e->getMessage());

			// User-friendly message
			$this->messageManager->addErrorMessage(
				__('Something went wrong while saving your data. Please try again.')
			);
			$_SESSION['error_msg'] = 'Something went wrong while saving your data. Please try again.';

			return $this->_redirect($this->_redirect->getRefererUrl());
		}

		// Final redirect
		return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
			->setPath($url);
	}

}
