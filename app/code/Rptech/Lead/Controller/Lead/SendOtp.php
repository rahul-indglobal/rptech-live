<?php

namespace Rptech\Lead\Controller\Lead;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Session\SessionManager;
use Rptech\Webinar\Model\OtpFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

class SendOtp extends Action
{
	protected $resultJsonFactory;
	protected $transportBuilder;
	protected $session;
	protected OtpFactory $otpFactory;
	protected $date;

	public function __construct(
		Context          $context,
		JsonFactory      $resultJsonFactory,
		TransportBuilder $transportBuilder,
		SessionManager   $session,
		OtpFactory       $otpFactory,
		DateTime         $date
	)
	{
		$this->resultJsonFactory = $resultJsonFactory;
		$this->transportBuilder  = $transportBuilder;
		$this->session           = $session;
		$this->otpFactory        = $otpFactory;
		$this->date              = $date;
		parent::__construct($context);
	}

	public function execute()
	{
		$email  = $this->getRequest()->getParam('email');
		$result = $this->resultJsonFactory->create();

		$scopeConfig = $this->_objectManager->get(ScopeConfigInterface::class);
		$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return $result->setData(['success' => false, 'message' => 'Invalid email']);
		}
//		$senderEmail = $scopeConfig->getValue('trans_email/ident_support/email', $storeScope);
//		$senderName  = $scopeConfig->getValue('trans_email/ident_support/name', $storeScope);
//		$subject = "Your OTP Code";
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
		try {
			$otpModel->save();
			// store OTP in session
			//$this->session->setOtpCode($otp);
			$transport = $this->transportBuilder
				->setTemplateIdentifier('rptech_lead_otp_verify_email')
				->setTemplateOptions([
					'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
					'store' => 1
				])
				->setTemplateVars(['otp' => $otp])
				->setFrom('support')
				->addTo($email)
				->getTransport();

			$transport->sendMessage();

			return $result->setData(['success' => true]);
		}catch (\Exception $exception){
			return $result->setData(['success' => false, 'message' => $exception->getMessage()]);
		}
	}
}