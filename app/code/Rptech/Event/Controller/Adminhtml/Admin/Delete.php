<?php
/**
 * @author Rptech
 * @package Rptech_Event
 */
namespace Rptech\Event\Controller\Adminhtml\Admin;

use Rptech\Event\Model\EventFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\Event\Controller\Adminhtml\Admin
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_Event::save';

    /**
     * @var EventFactory
     */
    private $eventFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param EventFactory $eventFactory
     */
    public function __construct(
        Action\Context $context,
        EventFactory $eventFactory
    ) {
        $this->eventFactory = $eventFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->eventFactory->create();
                $model->load($entityId);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The Event record has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Event to delete.'));
        return $resultRedirect->setPath('*/*');
    }
}
