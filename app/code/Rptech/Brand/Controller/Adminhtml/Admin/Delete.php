<?php
/**
 * @author Rptech
 * @package Rptech_Brand
 */
namespace Rptech\Brand\Controller\Adminhtml\Admin;

use Rptech\Brand\Model\BrandFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\Brand\Controller\Adminhtml\Admin
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_Brand::delete';

    /**
     * @var BrandFactory
     */
    private $brandFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param BrandFactory $brandFactory
     */
    public function __construct(
        Action\Context $context,
        BrandFactory $brandFactory
    ) {
        $this->brandFactory = $brandFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->brandFactory->create();
                $model->load($entityId);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The Brand record has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Brand to delete.'));
        return $resultRedirect->setPath('*/*');
    }
}
