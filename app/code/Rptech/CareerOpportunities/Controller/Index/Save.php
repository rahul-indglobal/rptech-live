<?php

namespace Rptech\CareerOpportunities\Controller\Index;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Rptech\CareerOpportunities\Api\CareerOpportunitiesRepositoryInterface;
use Rptech\CareerOpportunities\Api\Data\CareerOpportunitiesInterfaceFactory;
use Rptech\CareerOpportunities\Api\Data\CareerOpportunitiesInterface;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;


/**
 * Class Save
 * @package Rptech\CareerOpportunities\Controller\Index
 */
class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CareerOpportunitiesRepositoryInterface
     */
    protected $careerOpportunitiesRepositoryInterface;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var CareerOpportunitiesInterfaceFactory
     */
    protected $dataFactory;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;
    /**
     * @var Filesystem
     */
    protected $file;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var DirectoryList
     */
    protected $directoryList;
    
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
     * @param CareerOpportunitiesRepositoryInterface $careerOpportunitiesRepositoryInterface
     * @param DataObjectHelper $dataObjectHelper
     * @param CareerOpportunitiesInterfaceFactory $careerOpportunitiesInterfaceFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param Filesystem $file
     * @param StoreManagerInterface $storeManager
     * @param DirectoryList $directoryList
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        CareerOpportunitiesRepositoryInterface $careerOpportunitiesRepositoryInterface,
        DataObjectHelper $dataObjectHelper,
        CareerOpportunitiesInterfaceFactory $careerOpportunitiesInterfaceFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem $file,
        StoreManagerInterface $storeManager,
        DirectoryList $directoryList,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->careerOpportunitiesRepositoryInterface = $careerOpportunitiesRepositoryInterface;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataFactory = $careerOpportunitiesInterfaceFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->file = $file;
        $this->storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|string
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $fileData = $this->getRequest()->getFiles('cv_file');

	    $errors = [];

		// Required fields
	    $requiredFields = ['name', 'post', 'city', 'age', 'qualification'];
	    foreach ($requiredFields as $field) {
		    if (empty($data[$field])) {
			    $errors[$field] = ucfirst($field) . " is required.";
		    }
	    }

		// allowed posts
	    $allowedPosts = ['Marketing', 'Sales', 'Finance', 'Other'];
	    if (!empty($data['post']) && !in_array($data['post'], $allowedPosts)) {
		    $errors['post'] = "Invalid post selected.";
	    }

		// Optional fields validation (if they have value)
	    if (!empty($data['experience']) && !preg_match('/^\d{1,2}$/', $data['experience'])) {
		    $errors['experience'] = "Experience must be a maximum of 2 digits.";
	    }

	    if (!empty($data['age']) && !preg_match('/^\d{1,2}$/', $data['age'])) {
		    $errors['age'] = "Age must be a maximum of 2 digits.";
	    }

	    if (!empty($data['mobile']) && !preg_match('/^\d{10}$/', $data['mobile'])) {
		    $errors['mobile'] = "Mobile number must be 10 digits.";
	    }


	    // Special character validation
	    $specialCharFields = ['name', 'city', 'remark'];
	    foreach ($specialCharFields as $field) {
		    if (!empty($data[$field]) && !preg_match('/^[a-zA-Z0-9\s]+$/', $data[$field])) {
			    $errors[$field] = ucfirst($field) . " contains invalid characters.";
		    }
	    }
	    if (!empty($data['qualification']) && preg_match('/[^a-zA-Z0-9\s\+]/', $data['qualification'])) {
		    $errors['qualification'] = "Qualification contains invalid characters.";
	    }
		// Email validation
	    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
		    $errors['email'] = "Please enter a valid email address.";
	    }

		// CV file (optional, but you can validate type/size)
	    if (!empty($_FILES['cv_file']['name'])) {
		    $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
		    if (!in_array($_FILES['cv_file']['type'], $allowedTypes)) {
			    $errors['cv_file'] = "Invalid CV file type. Allowed: PDF, DOC, DOCX.";
		    }
	    }

		// Return or process errors
	    if (!empty($errors)) {
		    foreach ($errors as $field => $error) {
			    $this->messageManager->addErrorMessage($error);
		    }
		    $_SESSION['error_msg'] = implode("<br>", $errors);
		    $resultRedirect = $this->resultRedirectFactory->create();
		    $resultRedirect->setUrl("/contactus");
		    return $resultRedirect;
	    }

        if ($data) {
            $id = $data["entity_id"] ?? "";
            if (!empty($id)) {
                $model = $this->careerOpportunitiesRepositoryInterface->getById($id);
            } else {
                unset($data['entity_id']);
                $model = $this->dataFactory->create();
            }
	        $docPath = "";
            if ($fileData && !empty($fileData['name'])) {
                try {
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'cv_file']);
                    $uploader->setAllowedExtensions(['doc', 'docx', 'pdf']);
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = $this->file->getDirectoryRead(DirectoryList::MEDIA)
                        ->getAbsolutePath('career/');
                    $result = $uploader->save($path);
                    if (!$result) {
                        throw new LocalizedException(
                            __('File cannot be saved to path: $1', $path)
                        );
                    }
                    $baseUrl = $this->storeManager->getStore()->getBaseUrl();
                    $docPath = $baseUrl . 'media/career/' . $result['file'];
                    $data['cv_file'] = $docPath;
                } catch (\Exception $ex) {
	                $_SESSION['error_msg'] = $ex->getMessage();
                    return $ex->getMessage();
                }
            }
            try {
	            $this->dataObjectHelper->populateWithArray($model, $data, CareerOpportunitiesInterface::class);
                $result = $this->careerOpportunitiesRepositoryInterface->save($model);
                if($result){
                    $templateVar = [
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'post' => $data['post'],
                        'city' => $data['city'],
                        'age' => $data['age'],
                        'qualification' => $data['qualification'],
                        'mobile' => $data['mobile'],
                        'experience' => $data['experience'],
                        'cv_file' => $docPath,
                    ];
                    $this->sendMailNotification($templateVar);
                }
				$_SESSION['success_msg'] = 'Your Application has been submit. RPTech Team will get back to you soon. Thank you';
                $this->messageManager->addSuccessMessage(__('Your Application has been submit. RPTech Team will get back to you soon. Thank you'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
	            $_SESSION['error_msg'] = $e->getMessage();
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
	            $_SESSION['error_msg'] = $e->getMessage();
            } catch (\Exception $e) {
	            $_SESSION['error_msg'] = $e->getMessage();
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the data.')
                );
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl("/contactus");
        return $resultRedirect;
    }
    
    /**
     * @param $templateVar
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMailNotification($templateVar){
        $supportName = $this->scopeConfig->getValue('trans_email/ident_support/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $supportEmail = $this->scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $recepientName = $this->scopeConfig->getValue('rpt_general/career_email/recepient_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $recepientEmail = $this->scopeConfig->getValue('rpt_general/career_email/recepient_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('careeropportunities_email_template')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
            ->setTemplateVars($templateVar) // Pass necessary data to template
            ->setFrom(['email' => $supportEmail, 'name' => $supportName])
            ->addTo($recepientEmail, $recepientName)
            ->getTransport();
        $transport->sendMessage();
    }
}