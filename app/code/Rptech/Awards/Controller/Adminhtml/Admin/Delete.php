<?php
/**
 * @author Rptech
 * @package Rptech_Awards
 */
namespace Rptech\Awards\Controller\Adminhtml\Admin;

use Rptech\Awards\Model\AwardsFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\Awards\Controller\Adminhtml\Admin
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_Awards::delete';

    /**
     * @var AwardsFactory
     */
    private $awardFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param AwardsFactory $awardFactory
     */
    public function __construct(
        Action\Context $context,
        AwardsFactory $awardFactory
    ) {
        $this->awardFactory = $awardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->awardFactory->create();
                $model->load($entityId);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The Award record has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Award to delete.'));
        return $resultRedirect->setPath('*/*');
    }
}
