<?php
namespace Rptech\Leadership\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Rptech\Leadership\Model\ResourceModel\Leadership;

/**
 * Class Save
 * @package Rptech\Leadership\Controller\Adminhtml\Admin
 */
class Save  extends \Magento\Backend\App\Action
{
    /**
     * @var \Rptech\Leadership\Model\LeadershipFactory
     */
    protected $leaderFactory;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    public function __construct
    (
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \Rptech\Leadership\Model\LeadershipFactory $leadershipFactory
    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->leaderFactory = $leadershipFactory;
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
            /** @var Leadership $model */
            $model = $this->leaderFactory->create();
            $entityId = $this->getRequest()->getParam('entity_id');
            if ($entityId) {
                $model->load($entityId);
            }
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved this Leader.'));
                $this->dataPersistor->clear('leadership');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the address.'));
            }
            $this->dataPersistor->set('leadership', $data);
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
            $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getEntityId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect;
    }
}