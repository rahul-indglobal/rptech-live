<?php
/**
 * @author Rptech
 * @package Rptech_ServiceAddress
 */
namespace Rptech\ServiceAddress\Controller\Adminhtml\Address;

use Rptech\ServiceAddress\Model\AddressFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\ServiceAddress\Controller\Adminhtml\Address
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_ServiceAddress::save';

    /**
     * @var AddressFactory
     */
    private $addressFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param AddressFactory $addressFactory
     */
    public function __construct(
        Action\Context $context,
        AddressFactory $addressFactory
    ) {
        $this->addressFactory = $addressFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->addressFactory->create();
                $model->load($entityId);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The address record has been deleted.'));
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
