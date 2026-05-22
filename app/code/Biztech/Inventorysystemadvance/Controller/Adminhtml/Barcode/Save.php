<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{

    protected $supplierModel;
    protected $dateTime;
    protected $barcodeModel;

    /**
     * @param Context                                       $context
     * @param \Biztech\Inventorysystem\Model\Managesupplier $supplierModel
     * @param \Magento\Framework\Stdlib\DateTime\DateTime   $dateTime
     * @param \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel
     */
    public function __construct(
        Context $context,
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel
    ) {
    
        parent::__construct($context);
        $this->supplierModel = $supplierModel;
        $this->dateTime = $dateTime;
        $this->barcodeModel = $barcodeModel;
    }
    
    /**
     * This function is used for save the new barcode
     * @return void
     */
    public function execute()
    {

        $data = $this->getRequest()->getParams();
        if ($data) {
            try {
                $prodIdStr = $data['productid'];
                $prodIdArr = explode(",", $data['productid']);
                array_pop($prodIdArr);
                for ($i = 0; $i < count($prodIdArr); $i++) {
                    $sku = $data['prod_sku'][$prodIdArr[$i]];
                    $po = $data['purchase_order'][$prodIdArr[$i]];
                    $supID = $data['supplier'][$prodIdArr[$i]];
                    if ($supID != '') {
                        $supName = $this->supplierModel->load($supID)->getFirstName();
                    } else {
                        $supName = '';
                    }
                    $qty = $data['barcode_qty'][$prodIdArr[$i]];
                    if ($data['status'][$prodIdArr[$i]] == 1) {
                        $status = 'Enable';
                    } else {
                        $status = 'Disable';
                    }

                    if (isset($data['ud_barcode'][$prodIdArr[$i]]) && $data['ud_barcode'][$prodIdArr[$i]] != '') {
                        $barcode = $data['ud_barcode'][$prodIdArr[$i]];
                    } else {
                        $barcodeString = substr($sku, 0, 3) . $po . substr($supName, 0, 3) . $qty . substr($status, 0, 3);
                        $barcode = "BAR" . strtoupper(str_shuffle($barcodeString));
                    }

                    $objDate = $this->dateTime;
                    $date = $objDate->gmtDate();
                    /* save barcode */
                    $barcodeModel = $this->barcodeModel;
                    $barcodeModel->setBarcode($barcode);
                    $barcodeModel->setCreatedAt($date);
                    $barcodeModel->setUpdatedAt($date);
                    $barcodeModel->setProductId($prodIdArr[$i]);
                    $barcodeModel->setSupplier($supID);
                    $barcodeModel->setBarcodeQty($qty);
                    $barcodeModel->setPurchaseorderId($po);
                    $barcodeModel->setStatus($data['status'][$prodIdArr[$i]]);
                    $barcodeModel->save();
                }
                $this->messageManager->addSuccess(__('The barcode(s) has been created successfully.'));
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $arrChunks = explode(" ", $e->getMessage());
                if (in_array("Duplicate", $arrChunks)) {
                    $message = "Duplicate entry for barcode: " . $barcode;
                } else {
                    $message = $e->getMessage();
                }

                $this->messageManager->addException($e, $message);
            }
        }
        $this->_redirect('*/*/');
    }
}
