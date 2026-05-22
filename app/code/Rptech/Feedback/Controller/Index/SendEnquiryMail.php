<?php
    
    namespace Rptech\Feedback\Controller\Index;
    
    use Magento\Framework\Api\DataObjectHelper;
    use Magento\Framework\App\Action\Context;
    use Rptech\Feedback\Api\FeedbackRepositoryInterface;
    use Rptech\Feedback\Api\Data\FeedbackInterfaceFactory;
    use Rptech\Feedback\Api\Data\FeedbackInterface;
    use Magento\Framework\Mail\Template\TransportBuilder;
    use Magento\Framework\App\Config\ScopeConfigInterface;
    
    /**
     * Class Save
     * @package Rptech\Feedback\Controller\Index
     */
    class SendEnquiryMail extends \Magento\Framework\App\Action\Action
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
         * SendEnquiryMail constructor.
         * @param Context $context
         * @param DataObjectHelper $dataObjectHelper
         * @param TransportBuilder $transportBuilder
         * @param ScopeConfigInterface $scopeConfig
         */
        public function __construct(
            Context $context,
            DataObjectHelper $dataObjectHelper,
            TransportBuilder $transportBuilder,
            ScopeConfigInterface $scopeConfig
        )
        {
            $this->dataObjectHelper = $dataObjectHelper;
            $this->transportBuilder = $transportBuilder;
            $this->scopeConfig = $scopeConfig;
            parent::__construct($context);
        }
        
        public function execute()
        {
            $data = $this->getRequest()->getPostValue();
            if ($data) {
                $templateVar = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'mobile' => $data['mobile'],
                    'message' => $data['message'],
                    'city' => $data['city'],
                ];
                
                $this->sendMailNotification($templateVar);
                //$this->_redirect($this->_redirect->getRefererUrl());
                $this->messageManager->addSuccessMessage(
                    __("Your Enquiry is submitted successfully.")
                );
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }
        }
        
        /**
         * @param $templateVar
         * @throws \Magento\Framework\Exception\MailException
         */
        public function sendMailNotification($templateVar){
            $supportName = $this->scopeConfig->getValue('trans_email/ident_support/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $supportEmail = $this->scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $recepientName = $this->scopeConfig->getValue('rpt_general/feedback_email/recepient_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $recepientEmail = $this->scopeConfig->getValue('rpt_general/feedback_email/recepient_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('business_opportunitis_enquiry_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars($templateVar) // Pass necessary data to template
                ->setFrom(['email' => $supportEmail, 'name' => $supportName])
                ->addTo("enquiry@rptechindia.com", "Enquiry Team RP tech India")
                ->getTransport();
            $transport->sendMessage();
        }
    }
