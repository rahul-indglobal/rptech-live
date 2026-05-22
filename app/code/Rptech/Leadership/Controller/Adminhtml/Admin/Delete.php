<?php
/**
 * @author Rptech
 * @package Rptech_Leadership
 */
namespace Rptech\Leadership\Controller\Adminhtml\Admin;

use Rptech\Leadership\Model\LeadershipFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\Leadership\Controller\Adminhtml\Admin
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_Leadership::save';

    /**
     * @var LeadershipFactory
     */
    private $leadershipFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param LeadershipFactory $leadershipFactory
     */
    public function __construct(
        Action\Context $context,
        LeadershipFactory $leadershipFactory
    ) {
        $this->leadershipFactory = $leadershipFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->leadershipFactory->create();
                $model->load($entityId);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The Leader record has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a address to delete.'));
        return $resultRedirect->setPath('*/*');
    }
}
