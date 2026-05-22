<?php
/**
 * @author Rptech
 * @package Rptech_Media
 */
namespace Rptech\Media\Controller\Adminhtml\Admin;

use Rptech\Media\Model\MediaFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\Media\Controller\Adminhtml\Admin
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_Media::delete';

    /**
     * @var MediaFactory
     */
    private $mediaFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param MediaFactory $mediaFactory
     */
    public function __construct(
        Action\Context $context,
        MediaFactory $mediaFactory
    ) {
        $this->mediaFactory = $mediaFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->mediaFactory->create();
                $model->load($entityId);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The Media record has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Media to delete.'));
        return $resultRedirect->setPath('*/*');
    }
}
