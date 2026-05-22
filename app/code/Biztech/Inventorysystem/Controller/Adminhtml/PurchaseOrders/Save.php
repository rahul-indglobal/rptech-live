<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Biztech\Inventorysystem\Model\PurchaseordersFactory;
use Biztech\Inventorysystem\Model\PurchaseordersitemsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Save extends Action
{
    public $resultPageFactory;
    public $resultPage;
    public $_bizHelper;
    public $_poFactory;
    public $_poItemFactory;

    /**
     * @param Context                    $context
     * @param PageFactory                $resultPageFactory
     * @param BizHelper                  $bizHelper
     * @param PurchaseordersFactory      $poFactory
     * @param PurchaseordersitemsFactory $poItemFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        BizHelper $bizHelper,
        PurchaseordersFactory $poFactory,
        PurchaseordersitemsFactory $poItemFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_bizHelper = $bizHelper;
        $this->_poFactory = $poFactory;
        $this->_poItemFactory = $poItemFactory;
    }

    /**
     * This function is used for save the PO
     * @return Void
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            try {
                if (isset($data['product_id']) || isset($data['productid'])) {
                    if (isset($data['send_email']) && $data['send_email'] == 'on') {
                        $data['send_email'] = 1;
                    } else {
                        $data['send_email'] = 0;
                    }

                    /* if the user is admin */
                    if ($this->_bizHelper->isRequestAdmin()) {
                        $currentUser = $this->_bizHelper->getCurrentUser();
                        $firstName = $currentUser->getFirstname();
                        $lastName = $currentUser->getLastname();
                        $user = $firstName . " " . $lastName;
                    } else {
                        $user = "";
                    }

                    if (isset($data['order_incr_id'])) {
                        $this->createPOFromPendingOrders($data, $user);
                    } else {
                        $this->createPOFromProducts($data, $user);
                    }
                } else {
                    $this->messageManager->addError(__('Please select Product to create Purchase Order.'));
                    if (isset($data['order_incr_id'])) {
                        $this->_redirect('*/pendingorders/index');
                    } else {
                        $this->_redirect('*/pendingitems/index');
                    }
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
    }

    /**
     * This function is used for create PO of pending orders
     * @param  Array $data
     * @param  int $user
     * @return Array
     */
    public function createPOFromPendingOrders($data, $user)
    {
        $poArray = [];
        $soIncrId = [];
        for ($i = 0; $i < count($data['supplier']); $i++) {
            if (isset($poArray[$data['supplier'][$i]][$data['product_id'][$i]]) && is_array($poArray[$data['supplier'][$i]][$data['product_id'][$i]]) && $data['req_qty'][$i] != '') {
                $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['input_cost'] = $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['input_cost'] + $data['input_cost'][$i];
                $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['req_qty'] = $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['req_qty'] + $data['req_qty'][$i];
                $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['qty_ordered'] = $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['qty_ordered'] + $data['qty_ordered'][$i];
                $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['sales_order_id'] = $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['sales_order_id'] . "," . $data['order_incr_id_line'][$i];
                if ($data['input_cost'][$i] != '') {
                    $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['row_total'] = $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['row_total'] + ($data['input_cost'][$i] * $data['req_qty'][$i]);
                    $supTotal[$data['supplier'][$i]][] = $data['input_cost'][$i] * $data['req_qty'][$i];
                } else {
                    $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['row_total'] = $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['row_total'] + ($data['cost'][$i] * $data['req_qty'][$i]);
                    $supTotal[$data['supplier'][$i]][] = $data['cost'][$i] * $data['req_qty'][$i];
                }

                $soIncrId[$data['supplier'][$i]][] = $data['order_incr_id_line'][$i];
            } else if ($data['req_qty'][$i] != '') {
                $soIncrId[$data['supplier'][$i]][] = $data['order_incr_id_line'][$i];
                $poArray[$data['supplier'][$i]][$data['product_id'][$i]] = array('name' => $data['name'][$i], 'sku' => $data['sku'][$i], 'qty' => $data['qty'][$i], 'qty_ordered' => $data['qty_ordered'][$i], 'supplier' => $data['supplier'][$i], 'cost' => $data['cost'][$i], 'input_cost' => $data['input_cost'][$i], 'req_qty' => $data['req_qty'][$i], 'sales_order_id' => $data['order_incr_id_line'][$i], 'warehouse' => $data['warehouse'][$i]);
                if ($data['input_cost'][$i] != '') {
                    $rowTotal = $data['input_cost'][$i] * $data['req_qty'][$i];
                } else {
                    $rowTotal = $data['cost'][$i] * $data['req_qty'][$i];
                }

                $supTotal[$data['supplier'][$i]][] = $rowTotal;
                $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['row_total'] = $rowTotal;
            }
        }

        if (isset($poArray) && is_array($poArray) && !empty($poArray)) {
            $afterSavePOArray = $poArray;
            foreach ($poArray as $supID => $poData) {
                $soIncrIdArray = $soIncrId[$supID];
                $supTotalArray = $supTotal[$supID];
                $soIncrementID = implode(",", array_unique($soIncrIdArray));

                $total = array_sum($supTotalArray);
                $purOrdModel = $this->_poFactory->create();

                /* get last increment id form model */
                $getLastIncrID = $purOrdModel->getCollection()
                    ->setOrder('id', 'DESC')
                    ->getFirstItem();
                if ($incrementID = $getLastIncrID->getPurchaseOrderId()) {
                    $parts = explode("-", $incrementID);
                    $incrPart = $parts[1];
                    $increment = (int)$incrPart + 1;
                    $incrementID = "PO-" . $increment;
                } else {
                    $incrementID = 'PO-100000001';
                }

                /* save po data */
                $purOrdModel = $this->_poFactory->create();
                $purOrdModel->setPurchaseOrderId($incrementID);
                $purOrdModel->setSupplierId($supID);
                $purOrdModel->setSalesOrderId($soIncrementID);
                $purOrdModel->setStatus('Pending');
                $purOrdModel->setTotal($total);
                if (isset($data['po_comment'])) {
                    $purOrdModel->setComments($data['po_comment']);
                }
                $purOrdModel->setCreatedBy($user);
                $purOrdModel->setMailSent($data['send_email']);
                $purOrdModel->save();

                /* get last inserted id */
                $lastInsrtId = $purOrdModel->getId();

                foreach ($poData as $itemID => $itemData) {
                    $purOrdItmModel = $this->_poItemFactory->create();
                    $purOrdItmModel->setPurchaseOrderId($lastInsrtId);
                    $purOrdItmModel->setProductId($itemID);
                    $purOrdItmModel->setProductName($itemData['name']);
                    $purOrdItmModel->setWarehouseId($itemData['warehouse']);
                    $purOrdItmModel->setProductSku($itemData['sku']);
                    $purOrdItmModel->setQtyAvail($itemData['qty']);
                    $purOrdItmModel->setQtyOrdered($itemData['qty_ordered']);
                    $purOrdItmModel->setQtyPurchased($itemData['req_qty']);
                    $purOrdItmModel->setSalesOrderId($itemData['sales_order_id']);
                    $purOrdItmModel->setRowTotal($itemData['row_total']);

                    $unitCost = $itemData['row_total'] / $itemData['req_qty'];
                    $purOrdItmModel->setCost($unitCost);

                    $purOrdItmModel->save();
                }
                $afterSavePOArray[$supID]['grand_total'] = $total;
                $afterSavePOArray[$supID]['po_id'] = $lastInsrtId;
                $afterSavePOArray[$supID]['po_incr_id'] = $incrementID;
            }
            $this->_forward('afterSavePO', null, null, array('po_array' => $afterSavePOArray));
        }
    }

    /**
     * This function is used for create PO for products
     * @param  Array $data
     * @param  int $user
     * @return Void
     */
    public function createPOFromProducts($data, $user)
    {
        $error = 0;
        if (isset($data['purchaseordercreate'])) {
            $orgData = $data;
            unset($data);
            $data['product_id'] = explode(",", $orgData['productid']);
            array_pop($data['product_id']);
            for ($i = 0; $i < count($data['product_id']); $i++) {
                $data['name'][$i] = $orgData['name'][$data['product_id'][$i]];
                $data['sku'][$i] = $orgData['sku'][$data['product_id'][$i]];
                $data['cost'][$i] = $orgData['cost'][$data['product_id'][$i]];
                $data['qty'][$i] = $orgData['qty'][$data['product_id'][$i]];

                if (in_array($data['product_id'][$i], $orgData['supplier'])) {
                    $data['supplier'][$i] = $orgData['supplier'][$data['product_id'][$i]];
                } else {
                    $error = 1;
                }
                
                if (in_array($data['product_id'][$i], $orgData['warehouse'])) {
                    $data['warehouse'][$i] = $orgData['warehouse'][$data['product_id'][$i]];
                } else {
                    $error = 1;
                }
                
                $data['input_cost'][$i] = $orgData['input_cost'][$data['product_id'][$i]];
                if (isset($orgData['productqty'])) {
                    $data['req_qty'][$i] = $orgData['productqty'][$data['product_id'][$i]];
                } else {
                    $this->messageManager->addError(__('Unkown Exception Occured'));
                    $this->_redirect('*/purchaseorders/new');
                }
            }
            if ($error == 1) {
                $this->messageManager->addError(__('Please select supplier and warehouse for this product'));
                $this->_redirect('*/purchaseorders/new');
            }
        }
        $poArray = [];

        for ($i = 0; $i < count($data['supplier']); $i++) {
            if ($data['input_cost'][$i] != '') {
                if (isset($poArray[$data['supplier'][$i]][$data['product_id'][$i]])) {
                    $row_total = $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['row_total'] + ($data['input_cost'][$i] * $data['req_qty'][$i]);
                } else {
                    $row_total = ($data['input_cost'][$i] * $data['req_qty'][$i]);
                }

                $supTotal[$data['supplier'][$i]][] = $data['input_cost'][$i] * $data['req_qty'][$i];
            } else {
                if (isset($poArray[$data['supplier'][$i]][$data['product_id'][$i]])) {
                    $row_total = $poArray[$data['supplier'][$i]][$data['product_id'][$i]]['row_total'] + ($data['input_cost'][$i] * $data['req_qty'][$i]);
                } else {
                    $row_total = ($data['input_cost'][$i] * $data['req_qty'][$i]);
                }
                $supTotal[$data['supplier'][$i]][] = $data['cost'][$i] * $data['req_qty'][$i];
            }

            $poArray[$data['supplier'][$i]][$data['product_id'][$i]] = [
                'name' => $data['name'][$i],
                'sku' => $data['sku'][$i],
                'qty' => $data['qty'][$i],
                'supplier' => $data['supplier'][$i],
                'input_cost' => $data['input_cost'][$i],
                'req_qty' => $data['req_qty'][$i],
                'row_total' => $row_total,
                'warehouse' => $data['warehouse'][$i]
            ];
        }

        if (isset($poArray) && is_array($poArray) && !empty($poArray)) {
            $afterSavePOArray = $poArray;
            foreach ($poArray as $supID => $poData) {
                $poModel = $this->_poFactory->create();
                $getLastIncrID = $poModel->getCollection()
                    ->setOrder('id', 'DESC')
                    ->getFirstItem();
                if ($incrementID = $getLastIncrID->getPurchaseOrderId()) {
                    $parts = explode("-", $incrementID);
                    $incrPart = $parts[1];
                    $increment = (int)$incrPart + 1;
                    $incrementID = "PO-" . $increment;
                } else {
                    $incrementID = 'PO-100000001';
                }

                $supTotalArray = $supTotal[$supID];
                $total = array_sum($supTotalArray);

                $purOrdModel = $this->_poFactory->create();
                $purOrdModel->setPurchaseOrderId($incrementID);
                $purOrdModel->setSupplierId($supID);
                $purOrdModel->setStatus('Pending');
                $purOrdModel->setTotal($total);
                if (isset($data['po_comment'])) {
                    $purOrdModel->setComments($data['po_comment']);
                }
                $purOrdModel->setCreatedBy($user);
                if (isset($data['send_email'])) {
                    $purOrdModel->setMailSent($data['send_email']);
                }
                $purOrdModel->save();

                $lastInsrtId = $purOrdModel->getId();
                foreach ($poData as $itemID => $itemData) {
                    $purOrdItmModel = $this->_poItemFactory->create();
                    $purOrdItmModel->setPurchaseOrderId($lastInsrtId);
                    $purOrdItmModel->setProductId($itemID);
                    $purOrdItmModel->setWarehouseId($itemData['warehouse']);
                    $purOrdItmModel->setProductName($itemData['name']);
                    $purOrdItmModel->setProductSku($itemData['sku']);
                    $purOrdItmModel->setQtyAvail($itemData['qty']);
                    $purOrdItmModel->setQtyPurchased($itemData['req_qty']);
                    $purOrdItmModel->setRowTotal($itemData['row_total']);

                    $unitCost = $itemData['row_total'] / $itemData['req_qty'];
                    $purOrdItmModel->setCost($unitCost);

                    $purOrdItmModel->save();
                }

                $afterSavePOArray[$supID]['grand_total'] = $total;
                $afterSavePOArray[$supID]['po_id'] = $lastInsrtId;
                $afterSavePOArray[$supID]['po_incr_id'] = $incrementID;
            }
            $this->_forward('afterSavePO', null, null, array('po_array' => $afterSavePOArray));
        }
    }
}
