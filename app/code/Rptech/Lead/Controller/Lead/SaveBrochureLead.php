<?php

namespace Rptech\Lead\Controller\Lead;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Rptech\Lead\Model\DellGbLeadFactory;
use Psr\Log\LoggerInterface;

class SaveBrochureLead extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var DellGbLeadFactory
     */
    protected $dellGbLeadFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param DellGbLeadFactory $dellGbLeadFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        DellGbLeadFactory $dellGbLeadFactory,
        LoggerInterface $logger
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->dellGbLeadFactory = $dellGbLeadFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $name = $this->getRequest()->getParam('name');
        $email = $this->getRequest()->getParam('email');

        if (!$name || !$email) {
            return $result->setData([
                'success' => false,
                'message' => __('Please provide both name and email.')
            ]);
        }

        try {
            // Basic validation
            if (strlen($name) < 3) {
                return $result->setData([
                    'success' => false,
                    'message' => __('Name must be at least 3 characters.')
                ]);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $result->setData([
                    'success' => false,
                    'message' => __('Please provide a valid email address.')
                ]);
            }

            $model = $this->dellGbLeadFactory->create();
            $model->setData([
                'full_name' => strip_tags($name),
                'email' => strip_tags($email),
                'type' => 'Brochure Download'
            ]);

            $model->save();

            return $result->setData([
                'success' => true,
                'message' => __('Details saved successfully.')
            ]);

        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            return $result->setData([
                'success' => false,
                'message' => __('An error occurred while saving your details. Please try again.')
            ]);
        }
    }
}