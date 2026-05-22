<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Biztech\Inventorysystem\Model\PurchaseordersFactory;
use Biztech\Inventorysystem\Model\PurchaseordersitemsFactory;
use Biztech\Inventorysystem\Model\PurchaseordercommentsFactory;

class UpdatePurchaseOrderDetails extends Action
{
    protected $bizHelper;
    protected $_purchaseOrderFactory;
    protected $_purchaseOrderItemsFactory;
    protected $_purchaseOrderCommentsFactory;

    /**
     * @param Context                      $context
     * @param PurchaseordersFactory        $purchaseOrderFactory
     * @param PurchaseordersitemsFactory   $purchaseOrderItemsFactory
     * @param PurchaseordercommentsFactory $purchaseOrderCommentsFactory
     * @param BizHelper                    $bizHelper
     */
    public function __construct(
        Context $context,
        PurchaseordersFactory $purchaseOrderFactory,
        PurchaseordersitemsFactory $purchaseOrderItemsFactory,
        PurchaseordercommentsFactory $purchaseOrderCommentsFactory,
        BizHelper $bizHelper
    ) {
        $this->_purchaseOrderFactory = $purchaseOrderFactory;
        $this->_purchaseOrderItemsFactory = $purchaseOrderItemsFactory;
        $this->_purchaseOrderCommentsFactory = $purchaseOrderCommentsFactory;
        $this->bizHelper = $bizHelper;
        parent::__construct($context);
    }

    /**
     * This function is used for update PO details
     * @return Void
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getPost()) {
            try {
                for ($i = 0; $i < count($data['po_id']); $i++) {
                    /* save purchase order comments */
                    if ($data['po_comment'][$data['po_id'][$i]] != '') {
                        $poCommentModel = $this->_purchaseOrderCommentsFactory->create();
                        $poCommentModel->setPurchaseorderId($data['po_id'][$i]);
                        $poCommentModel->setPurchaseorderIncrId($data['po_incr_id'][$i]);
                        $poCommentModel->setComment($data['po_comment'][$data['po_id'][$i]]);
                        $poCommentModel->save();
                    }

                    /* save PO details */
                    $poModel = $this->_purchaseOrderFactory->create();
                    $poModel = $poModel->load($data['po_id'][$i]);
                    /* save required date in purchase orders */
                    if ($data['required_date'][$data['po_id'][$i]] != '') {
                        $poModel->setRequiredDate($data['required_date'][$data['po_id'][$i]]);
                    }
                    $poModel->setShipmentMethod($data['ship_method'][$data['po_id'][$i]]);
                    $poModel->setPaymentMethod($data['payment_method'][$data['po_id'][$i]]);
                    $poModel->save();

                    /*save warehouse in poItems*/
                    // $po


                    /*SEND MAIL*/
                    if (isset($data['send_email'][$data['po_id'][$i]]) && $data['send_email'][$data['po_id'][$i]] == 'on') {
                        $sendMail = $this->bizHelper->sendEmail($data['po_id'][$i]);
                    }
                }
                $this->messageManager->addSuccess(__('Purchase Order(s) created successfully.'));
                $this->_redirect('*/*/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('*/*/index');
            }
        }
    }
}
