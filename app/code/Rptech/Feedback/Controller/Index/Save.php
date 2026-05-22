<?php

namespace Rptech\Feedback\Controller\Index;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Rptech\Feedback\Api\FeedbackRepositoryInterface;
use Rptech\Feedback\Api\Data\FeedbackInterfaceFactory;
use Rptech\Feedback\Api\Data\FeedbackInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class Save
 * @package Rptech\Feedback\Controller\Index
 */
class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var FeedbackRepositoryInterface
     */
    protected $feedbackRepositoryInterface;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var FeedbackInterfaceFactory
     */
    protected $dataFactory;
    
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * Save constructor.
     * @param Context $context
     * @param FeedbackRepositoryInterface $feedbackRepositoryInterface
     * @param DataObjectHelper $dataObjectHelper
     * @param FeedbackInterfaceFactory $feedbackInterfaceFactory
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        FeedbackRepositoryInterface $feedbackRepositoryInterface,
        DataObjectHelper $dataObjectHelper,
        FeedbackInterfaceFactory $feedbackInterfaceFactory,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->feedbackRepositoryInterface = $feedbackRepositoryInterface;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataFactory = $feedbackInterfaceFactory;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
	    $email = isset($data['email']) ? $data['email'] : '';
	    if ($email) {
		    // Load model collection
		    $collection = $this->dataFactory->create()->getCollection()
			    ->addFieldToFilter('email', $email)
			    ->addFieldToFilter('created_at', ['gteq' => date('Y-m-d H:i:s', strtotime('-1 day'))])
			    ->setPageSize(1);

		    if ($collection->getSize() > 0) {
			    // Email already submitted in last 24 hours
			    $this->messageManager->addErrorMessage(__('You have already submitted this form in the last 24 hours.'));
				$_SESSION['error_msg'] = 'It looks like you’ve already submitted this recently. We’re on it!';
			    return $this->resultRedirectFactory->create()->setUrl("/contactus");
		    }
	    }
	    $_SESSION['req_data'] = $data;
	    $allowedTypes = ['Feedback', 'Enquiry', 'Service & Warranty'];
	    $allowedStates = [
		    'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat',
		    'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala',
		    'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha(Orissa)',
		    'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Tripura', 'Uttar Pradesh', 'Uttarakhand',
		    'West Bengal'
	    ];
	    $optionalFields = ['address', 'mobile', 'pincode', 'state', 'city'];
	    $validationRules = [
		    'name'        => '/^[a-zA-Z\s]+$/',                    // letters + spaces
		    'description' => '/^[a-zA-Z0-9\s,.\-!@#()?]+$/',      // alphanumeric + punctuation
		    'email'       => FILTER_VALIDATE_EMAIL,                // email validation
		    'address'     => '/^[a-zA-Z0-9\s,.-]+$/',             // letters, numbers, comma, dot, hyphen
		    'phone'       => '/^[0-9]{10}$/',                      // exactly 10 digits
		    'mobile'      => '/^[0-9]{10}$/',                      // exactly 10 digits
		    'pincode'     => '/^[0-9]{6}$/',                       // exactly 6 digits
		    'type'        => $allowedTypes,                        // select option
		    'state'       => $allowedStates,                       // select option
		    'city'        => '/^[a-zA-Z\s]+$/'                     // letters + spaces
	    ];
	    foreach ($validationRules as $field => $rule) {
		    if (!isset($data[$field]) || (in_array($field, $optionalFields) && trim($data[$field]) === '')) {
			    continue;
		    }
		    $value = trim($data[$field]);

		    // Handle select options (array)
		    if (is_array($rule)) {
			    if (!in_array($value, $rule)) {
				    $this->messageManager->addErrorMessage(__("Invalid value for %1.", $field));
				    $_SESSION['error_msg'] = "Invalid value for $field.";
				    $resultRedirect = $this->resultRedirectFactory->create();
				    $resultRedirect->setUrl("/contactus");
				    return $resultRedirect;
			    }
			    continue;
		    }

		    // Handle email
		    if ($rule === FILTER_VALIDATE_EMAIL) {
			    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				    $this->messageManager->addErrorMessage(__("Invalid email format."));
				    $_SESSION['error_msg'] = "Invalid email format.";
				    $resultRedirect = $this->resultRedirectFactory->create();
				    $resultRedirect->setUrl("/contactus");
				    return $resultRedirect;
			    }
			    continue;
		    }

		    // Regex validation for text/numeric
		    if (!preg_match($rule, $value)) {
			    $this->messageManager->addErrorMessage(__("Invalid characters in %1.", $field));
			    $_SESSION['error_msg'] = "Invalid characters in $field";
				if ($field == 'pincode') {
					$_SESSION['error_msg'] = "Invalid characters in $field or less then 6 digit.";
				}
				if ($field == 'mobile' || $field == 'phone') {
					$_SESSION['error_msg'] = "Invalid characters in $field or less then 10 digit.";
				}
			    $resultRedirect = $this->resultRedirectFactory->create();
			    $resultRedirect->setUrl("/contactus");
			    return $resultRedirect;
		    }

		    // Overwrite validated value
		    $data[$field] = $value;
	    }

        if ($data) {
            $id = $data["entity_id"] ?? "";
            if (!empty($id)) {
                $model = $this->feedbackRepositoryInterface->getById($id);
            } else {
                unset($data['entity_id']);
                $model = $this->dataFactory->create();
            }
            try {
                $this->dataObjectHelper->populateWithArray($model, $data, FeedbackInterface::class);
                $result = $this->feedbackRepositoryInterface->save($model);

	            $logger = new Logger('feedback_enquiry');
	            $logger->pushHandler(
		            new StreamHandler(BP . '/var/log/feedback_enquiry.log', Logger::DEBUG)
	            );

	            // Logs
	            $logger->info('Feedback enquiry submitted');
                if($result){
                    $templateVar = [
                        'type' => $data['type'],
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                        'address' => $data['address'],
                        'mobile' => $data['mobile']
                    ];
	                $logger->info('Before Mail Log');
                    $this->sendMailNotification($templateVar);
	                $logger->info('After Mail Log');
                }
	            $_SESSION['success_msg'] = 'Your feedback has been sent. RPTech Team will get back to you soon. Thank you';
                $this->messageManager->addSuccessMessage(__('Your feedback has been sent. RPTech Team will get back to you soon. Thank you'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
	            $_SESSION['error_msg'] = $e->getMessage();
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
	            $_SESSION['error_msg'] = $e->getMessage();
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
	            $_SESSION['error_msg'] = "Something went wrong while saving the data.";
            }
            //$this->_redirect($this->_redirect->getRefererUrl());
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl("/contactus");
            return $resultRedirect;
        }
    }
    
    /**
     * @param $templateVar
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMailNotification($templateVar){
	    $logger = new Logger('feedback_enquiry');
	    $logger->pushHandler(
		    new StreamHandler(BP . '/var/log/feedback_enquiry.log', Logger::DEBUG)
	    );

        $supportName = $this->scopeConfig->getValue('trans_email/ident_support/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $supportEmail = $this->scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $recepientName = $this->scopeConfig->getValue('rpt_general/feedback_email/recepient_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $recepientEmail = $this->scopeConfig->getValue('rpt_general/feedback_email/recepient_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$logger->info('supportEmail => '. $supportEmail);
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('feedback_email_template')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
            ->setTemplateVars($templateVar) // Pass necessary data to template
            ->setFrom(['email' => $supportEmail, 'name' => $supportName])
            ->addTo($recepientEmail, $recepientName)
            ->getTransport();
        $transport->sendMessage();
    }
}
