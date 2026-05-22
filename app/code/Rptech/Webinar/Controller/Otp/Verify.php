<?php
namespace Rptech\Webinar\Controller\Otp;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Rptech\Webinar\Model\OtpFactory;
use Rptech\Webinar\Model\ResourceModel\Otp\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Verify extends Action
{
	protected $resultJsonFactory;
	protected $otpFactory;
	protected $collectionFactory;
	protected $timezone;
    protected $date;

	public function __construct(
		Context $context,
		JsonFactory $resultJsonFactory,
		OtpFactory $otpFactory,
		CollectionFactory $collectionFactory,
		TimezoneInterface $timezone,
        DateTime $date
	) {
		$this->resultJsonFactory = $resultJsonFactory;
		$this->otpFactory = $otpFactory;
		$this->collectionFactory = $collectionFactory;
		$this->timezone = $timezone;
        $this->date = $date;
		parent::__construct($context);
	}

	public function execute()
	{
		$result = $this->resultJsonFactory->create();
		$email = trim($this->getRequest()->getParam('email'));
		$otp = trim($this->getRequest()->getParam('otp'));

		if (!$email || !$otp) {
			return $result->setData(['success' => false, 'message' => 'Email and OTP are required.']);
		}

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

		if ($otpRow->getOtpCode() !== $otp) {
			// Optionally: increment attempt count
			$otpRow->setAttemptCount($otpRow->getAttemptCount() + 1)->save();
			return $result->setData(['success' => false, 'message' => 'Invalid OTP.']);
		}

		// OTP matches — mark verified
		$otpRow->setVerified(1)->save();
		return $result->setData(['success' => true, 'message' => 'OTP verified successfully.']);
	}
}
