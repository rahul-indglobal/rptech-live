<?php

namespace Rptech\Lead\Controller\Lead;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Session\SessionManager;
use Rptech\Webinar\Model\OtpFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Rptech\Webinar\Model\ResourceModel\Otp\CollectionFactory;

class VerifyOtp extends Action
{
	protected $resultJsonFactory;
	protected $session;
	protected OtpFactory $otpFactory;
	protected CollectionFactory $collectionFactory;
	protected DateTime $date;

	public function __construct(
		Context $context,
		JsonFactory $resultJsonFactory,
		SessionManager $session,
		OtpFactory $otpFactory,
		CollectionFactory $collectionFactory,
		DateTime $date
	) {
		$this->resultJsonFactory = $resultJsonFactory;
		$this->session = $session;
		$this->otpFactory = $otpFactory;
		$this->collectionFactory = $collectionFactory;
		$this->date = $date;
		parent::__construct($context);
	}

	public function execute()
	{
		$reqOtp = $this->getRequest()->getParam('otp');
		$email = $this->getRequest()->getParam('email');
		//$savedOtp = $this->session->getOtpCode();
		$result = $this->resultJsonFactory->create();

		// Load latest OTP for this email
		$collection = $this->collectionFactory->create();
		$collection->addFieldToFilter('email', $email)
			->setOrder('created_at', 'DESC')
			->setPageSize(1);

		$otpRow = $collection->getFirstItem();

		if (!$otpRow || !$otpRow->getOtpId()) {
			return $result->setData(['success' => false, 'message' => 'No OTP found.']);
		}

		if ($otpRow->getVerified()) {
			return $result->setData(['success' => false, 'message' => 'OTP already used.']);
		}

		$currentTime = $this->date->gmtDate();
		if (strtotime($currentTime) > strtotime($otpRow->getExpiresAt())) {
			return $result->setData(['success' => false, 'message' => 'OTP expired.']);
		}

		if ($otpRow->getOtpCode() !== $reqOtp) {
			// Optionally: increment attempt count
			$otpRow->setAttemptCount($otpRow->getAttemptCount() + 1)->save();
			return $result->setData(['success' => false, 'message' => 'Invalid OTP.']);
		}

		// OTP matches — mark verified
		$otpRow->setVerified(1)->save();

		if ($reqOtp == $otpRow->getOtpCode()) {
			return $result->setData(['success' => true]);
		}

		return $result->setData(['success' => false]);
	}
}
