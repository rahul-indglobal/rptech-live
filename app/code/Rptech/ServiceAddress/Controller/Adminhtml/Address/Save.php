<?php

namespace Rptech\ServiceAddress\Controller\Adminhtml\Address;

use Rptech\ServiceAddress\Model\Address;
use Rptech\ServiceAddress\Model\AddressFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use RuntimeException;

class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var AddressFactory
     */
    private $addressFactory;

    const ADMIN_RESOURCE = 'Boolfly_Brand::save';

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param AddressFactory $addressFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        AddressFactory $addressFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->addressFactory = $addressFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }
            /** @var Address $model */
            $model = $this->addressFactory->create();
            $entityId = $this->getRequest()->getParam('entity_id');
            if ($entityId) {
                $model->load($entityId);
            }
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved this address.'));
                $this->dataPersistor->clear('address');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the address.'));
            }
            $this->dataPersistor->set('address', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
        }
        return $resultRedirect->setPath('*/*/');
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
            //$resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getEntityId()]);
            $resultRedirect->setPath('*/*/edit');
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect;
    }
}
