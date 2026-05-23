<?php

namespace Rptech\Lead\Controller\Lead;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Rptech\Lead\Model\DellGbLeadFactory;
use Rptech\Lead\Model\DellGbLead;
use Rptech\Lead\Model\FormValidatorEnquiry;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;

class SubmitEnquiry extends Action
{
	protected DellGbLeadFactory $dellGbLeadFactory;
	protected FormValidatorEnquiry $formValidator;
	protected LoggerInterface $logger;
	protected StoreManagerInterface $storeManager;


	public function __construct(
		Context $context,
		DellGbLeadFactory $dellGbLeadFactory,
		FormValidatorEnquiry $formValidator,
		LoggerInterface $logger,
		StoreManagerInterface $storeManager,
		TransportBuilder $transportBuilder
	)
	{
		$this->dellGbLeadFactory = $dellGbLeadFactory;
		$this->formValidator = $formValidator;
		$this->logger = $logger;
		$this->storeManager = $storeManager;
		$this->transportBuilder = $transportBuilder;
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

			/** @var DellGbLead $model */
			$model = $this->dellGbLeadFactory->create();

			// NEVER save raw $post without filtering allowed fields
			$allowedFields = [
				'type', 'full_name', 'company', 'email', 'role', 'industry'
			];

			$cleanData = array_intersect_key($post, array_flip($allowedFields));

			// Handle "Other" fields logic
			if (!empty($post['role_other'])) {
				$cleanData['role'] = 'Other - ' . $post['role_other'];
			}
			if (!empty($post['industry_other'])) {
				$cleanData['industry'] = 'Other - ' . $post['industry_other'];
			}

			$model->setData($cleanData);

			// Save inside try/catch for better error control
			if($model->save()) {
				$this->messageManager->addSuccessMessage(__('Lead saved successfully.'));
				$_SESSION['success_msg'] = 'Lead saved successfully.';

				$this->sendEmailAlert($model);
			};

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
		return $this->_redirect($this->_redirect->getRefererUrl());
	}

	public function sendEmailAlert($model){
		$templateVars = [
			'customer_name' =>  $model->getFullName(),
		];

		$transport = $this->transportBuilder
			->setTemplateIdentifier('dell_gb_lead_mail') // Must match ID in email_templates.xml
			->setTemplateOptions([
				'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
				'store' => $this->storeManager->getStore()->getId()
			])
			->setTemplateVars($templateVars)
			->setFrom('support')
			->addTo($model->getEmail(), $model->getFullname())
			->getTransport();

		$transport->sendMessage();
	}
}
