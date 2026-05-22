<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Magento\Framework\View\Result\PageFactory;

class UpdatePOComment extends Action
{
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $_purchaseordercommentsModel;

    /**
     * @param Context                                          $context
     * @param PageFactory                                      $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Biztech\Inventorysystem\Model\Purchaseorders    $purchaseordercommentsModel
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Biztech\Inventorysystem\Model\Purchaseorders $purchaseordercommentsModel
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_purchaseordercommentsModel = $purchaseordercommentsModel;
    }

    /**
     * This function is used for updat the PO comments
     * @return Json
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            try {
                if (empty($data['po_comment'])) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Please enter a comment.'));
                }

                $po_comment = $data['po_comment'];
                $poID = $data['po_id'];
                $po_increment = $data['po_increment'];

                $poCommentModel = $this->_purchaseordercommentsModel;
                $poCommentModel->setPurchaseorderId($poID);
                $poCommentModel->setPurchaseorderIncrId($po_increment);
                $poCommentModel->setComment($po_comment);
                $poCommentModel->save();
                return $this->resultPageFactory->create();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $response = ['error' => true, 'message' => $e->getMessage()];
            } catch (\Exception $e) {
                $response = ['error' => true, 'message' => __('We cannot add order history.')];
            }
            if (is_array($response)) {
                $resultJson = $this->resultJsonFactory->create();
                $resultJson->setData($response);
                return $resultJson;
            }
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
