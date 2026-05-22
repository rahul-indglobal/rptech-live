<?php

namespace Rptech\FinancialReport\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Rptech\FinancialReport\Model\FinancialReportDocFactory;

class ImageDelete extends \Magento\Backend\App\Action
{
    /**
     * @var FinancialReportDocFactory
     */
    protected $financialReportDocFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(
        Action\Context $context,
        FinancialReportDocFactory $financialReportDocFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        $this->financialReportDocFactory = $financialReportDocFactory;
        $this->resultJsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $response = ['success' => false, 'message' => 'something went wrong'];
        if(isset($data['id'])){
            $model = $this->financialReportDocFactory->create();
            $model->load($data['id']);
            if ($model->delete()) {
                $response['success'] = true;
                $response['message'] = __("Document delete Successfully");
                $this->messageManager->addSuccessMessage(__("Document delete Successfully"));
            }
        }
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData(['response' => $response]);
    }
}
