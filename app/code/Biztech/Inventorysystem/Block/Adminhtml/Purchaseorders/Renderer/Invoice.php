<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Invoice extends AbstractRenderer
{
    protected $_orderModel;
    protected $_storeManager;
    protected $_urlInterface;
    protected $purchaseinvoiceModel;

    /**
     * @param \Magento\Sales\Model\Order                 $orderModel
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface            $urlInterface
     */
    public function __construct(
        \Magento\Sales\Model\Order $orderModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Biztech\Inventorysystem\Model\Purchaseinvoice $purchaseinvoiceModel
    ) {
        $this->_orderModel = $orderModel;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
        $this->_purchaseinvoiceModel = $purchaseinvoiceModel;
    }
    
      
    /**
     * @param  DataObject $row
     * @return Void
     */
    public function render(DataObject $row)
    {
        $txtbox = '';
        $setLinks = array();
        if ($this->getColumn()->getIndex()=='invoiced') {
            $poId = $row->getPurchaseOrderId();
            $isInvoiced = $row->getInvoiced();
            $invoiceId = '';
            if ($isInvoiced == 1) {                
                $pinvoice = $this->_purchaseinvoiceModel->load($poId, 'purchase_order_id');
                
                $setLinks[] = "<a title='".$pinvoice->getInvoiceIncrId()."' href='".$this->_urlInterface->getUrl('inventorysystem/purchaseinvoice/view', ['id'=>$pinvoice->getId()])."'>".$pinvoice->getInvoiceIncrId()."</a>";
            }
            $txtbox = "<span>".implode(",", $setLinks)."</span>";
        }
        return $txtbox;
    }
}
