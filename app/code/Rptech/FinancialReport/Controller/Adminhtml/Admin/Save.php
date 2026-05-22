<?php

namespace Rptech\FinancialReport\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Rptech\FinancialReport\Model\FinancialReport;
use Rptech\FinancialReport\Model\FinancialReportDoc;

/**
 * Class Save
 * @package Rptech\FinancialReport\Controller\Adminhtml\Admin
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Rptech\FinancialReport\Model\FinancialReportFactory
     */
    protected $financialReportFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var \Rptech\FinancialReport\Model\FinancialReportDocFactory
     */
    protected $financialReportDocFactory;

    public function __construct(
        Action\Context $context,
        \Rptech\FinancialReport\Model\FinancialReportFactory $financialReportFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Rptech\FinancialReport\Model\FinancialReportDocFactory $financialReportDocFactory
    )
    {
        $this->financialReportFactory = $financialReportFactory;
        $this->dataPersistor = $dataPersistor;
        $this->financialReportDocFactory = $financialReportDocFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }
            /** @var FinancialReport $model */
            $model = $this->financialReportFactory->create();
            $entityId = $this->getRequest()->getParam('entity_id');
            if ($entityId) {
                $model->load($entityId);
            }
            $model->setData($data);
            try {
                $model->save();
                if($model->save()){
                    if (isset($data['document'])) {
                        foreach ($data['document'] as $document) {
                            $docModel = $this->financialReportDocFactory->create();
                            $docModel->setParentId($model->getEntityId());
                            $docModel->setDocument($document['url']);
                            $docModel->save();
                        }
                    }
                    $this->messageManager->addSuccessMessage(__('You saved the Report.'));
                }
                $this->dataPersistor->clear('financial_report');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getTraceAsString());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e->getTraceAsString(), __('Something went wrong while saving the Financial Report.'));
            }
            $this->dataPersistor->set('financial_report', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
        }
        return $resultRedirect->setPath('*/*/edit');
    }

    /**
     * @param $model
     * @param $data
     * @param $resultRedirect
     * @return mixed
     */
    public function processReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';
        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getEntityId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect;
    }
}