<?php
/** 
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Observer;

use Biztech\Inventorysystem\Model\Supplierproducttemp;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductApprove implements ObserverInterface
{
    protected $_supplierTempProduct;
    protected $_logger;

    /**
     * @param Supplierproducttemp      $supplierProduct
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Supplierproducttemp $supplierProduct,
        \Psr\Log\LoggerInterface $logger //log injection
    )
    {
        $this->_supplierTempProduct = $supplierProduct;
        $this->_logger = $logger;

    }

    /** 
     * This function is used for the approve the producyts
     * @param  Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $prod_id = $product->getId();

        $pendingProduct = $this->_supplierTempProduct->getCollection()->addFieldToFilter('product_id', $prod_id)->getData();
        if (!empty($pendingProduct) && $pendingProduct[0]['new_prod_flag'] == 1 && $pendingProduct[0]['approve_status'] == 0) {            
            if ($product->getStatus() == 1) {
                $supplierProduct = $this->_supplierTempProduct;
                $supplierProduct->setId($pendingProduct[0]['id']);
                $supplierProduct->setApproveStatus(1);
                $supplierProduct->setNewProdFlag(0);
                $supplierProduct->save();
            }
        }
    }
}