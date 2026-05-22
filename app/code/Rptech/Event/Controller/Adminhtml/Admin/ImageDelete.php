<?php

namespace Rptech\Event\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Rptech\Event\Model\EventImageFactory;

/**
 * Class ImageDelete
 * @package Rptech\Event\Controller\Adminhtml\Admin
 */
class ImageDelete extends \Magento\Backend\App\Action
{
    /**
     * @var EventImageFactory
     */
    protected $eventImageFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(
        Action\Context $context,
        EventImageFactory $eventImageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        $this->eventImageFactory = $eventImageFactory;
        $this->resultJsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $response = ['success' => false, 'message' => 'something went wrong'];
        if(isset($data['id'])){
            $model = $this->eventImageFactory->create();
            $model->load($data['id']);
            if ($model->delete()) {
                $response['success'] = true;
                $response['message'] = __("Image delete Successfully");
                $this->messageManager->addSuccessMessage(__("Image delete Successfully"));
            }
        }
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData(['response' => $response]);
    }
}
