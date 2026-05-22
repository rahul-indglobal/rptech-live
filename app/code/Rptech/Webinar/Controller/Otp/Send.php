<?php

namespace Rptech\Webinar\Controller\Otp;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Rptech\Webinar\Model\OtpFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Rptech\Webinar\Helper\MailHelper;
use Rptech\Webinar\Model\ResourceModel\Otp\CollectionFactory;

class Send extends Action
{
	protected $resultJsonFactory;
	protected $otpFactory;
	protected $transportBuilder;
	protected $date;
    protected $mailHelper;
    protected $collectionFactory;

    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		JsonFactory $resultJsonFactory,
		OtpFactory $otpFactory,
		TransportBuilder $transportBuilder,
		DateTime $date,
        MailHelper $mailHelper,
        CollectionFactory $collectionFactory
	) {
		$this->resultJsonFactory = $resultJsonFactory;
		$this->otpFactory = $otpFactory;
		$this->transportBuilder = $transportBuilder;
		$this->date = $date;
        $this->mailHelper = $mailHelper;
        $this->collectionFactory = $collectionFactory;
		parent::__construct($context);
	}

	public function execute()
	{
		$result = $this->resultJsonFactory->create();
		$email = $this->getRequest()->getParam('email');
		$name = $this->getRequest()->getParam('name');

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('email', $email);
        $collection->addFieldToFilter('verified', 1);

        if ($collection->getSize()) {
            return $result->setData(['success' => false, 'message' => 'This email already exists, please try a different email ID']);
        }

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return $result->setData(['success' => false, 'message' => 'Invalid email.']);
		}

		$otp = rand(100000, 999999);
		$otpModel = $this->otpFactory->create();
		$otpModel->setData([
			'email' => $email,
			'otp_code' => $otp,
			'verified' => 0,
			'attempt_count' => 0,
			'expires_at' => date('Y-m-d H:i:s', strtotime('+2 minutes')),
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'session_id' => session_id(),
			'created_at' => $this->date->gmtDate()
		]);
		$otpModel->save();

		// Send email
		try {
            $emailTemplateVariables = [
                "otp" => $otp,
                "name" => $name,
            ];

            $receiverInfo = [
                "email" => $email,
                "name" => $name,
            ];
            $this->mailHelper->mailSend($emailTemplateVariables, $receiverInfo);
			return $result->setData(['success' => true, 'message' => __('OTP sent')]);
		} catch (\Exception $e) {
			return $result->setData(['success' => true, 'message' => __('Failed to send OTP')]);
		}
	}
}
