<?php
/**
 * @author Rptech
 * @package Rptech_FinancialReport
 */
namespace Rptech\FinancialReport\Controller\Adminhtml\Admin;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Rptech\FinancialReport\Model\FinancialReportFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Rptech\FinancialReport\Controller\Adminhtml\Admin
 */
class Edit extends Action
{
    /**
     * @var FinancialReportFactory
     */
    protected $financialReportFactory;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param FinancialReportFactory $financialReportFactory
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        FinancialReportFactory $financialReportFactory,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->financialReportFactory = $financialReportFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        $model = $this->financialReportFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Report is no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        //Set entered data if was error when do save
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->coreRegistry->register('financial_report',$model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_FinancialReport::FinancialReport')
            ->addBreadcrumb(__('FinancialReport'), __('Edit'))
            ->addBreadcrumb(__('Manage FinancialReport'), __('Manage FinancialReport'));
        $resultPage->getConfig()->getTitle()->prepend(__('FinancialReport'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add FinancialReport'));
        return $resultPage;
    }
}