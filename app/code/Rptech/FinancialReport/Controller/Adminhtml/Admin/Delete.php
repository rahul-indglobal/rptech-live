<?php
/**
 * @author Rptech
 * @package Rptech_FinancialReport
 */
namespace Rptech\FinancialReport\Controller\Adminhtml\Admin;

use Rptech\FinancialReport\Model\FinancialReportFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\FinancialReport\Controller\Adminhtml\Admin
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_FinancialReport::delete';

    /**
     * @var FinancialReportFactory
     */
    private $financialReportFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param FinancialReportFactory $financialReportFactory
     */
    public function __construct(
        Action\Context $context,
        FinancialReportFactory $financialReportFactory
    ) {
        $this->financialReportFactory = $financialReportFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->financialReportFactory->create();
                $model->load($entityId);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The Finacial Report record has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Financial Report to delete.'));
        return $resultRedirect->setPath('*/*');
    }
}
