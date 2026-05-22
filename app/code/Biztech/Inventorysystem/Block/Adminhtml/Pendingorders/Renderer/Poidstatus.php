<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Pendingorders\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Biztech\Inventorysystem\Model\PurchaseordersFactory;
use Magento\Backend\Block\Context;

class Poidstatus extends AbstractRenderer
{
    protected $_poFactory;

    /**
     * @param Context               $context
     * @param PurchaseordersFactory $poFactory
     */
    public function __construct(
        Context $context,
        PurchaseordersFactory $poFactory
    ) {
        parent::__construct($context);
        $this->_poFactory = $poFactory;
    }

    /**
     * This function is used for PO status
     * @param  DataObject $row
     * @return String
     */
    public function render(DataObject $row)
    {
        $txtBox = '';
        if ($this->getColumn()->getIndex() == 'po_id_status') {
            $poModel = $this->_poFactory->create();
            $po = $poModel->getCollection()->addFieldToFilter('sales_order_id', $row->getIncrementId());

            foreach ($po->getData() as $key => $value) {
                $poModel1 = $this->_poFactory->create();
                $po1 = $poModel1->load($value['purchase_order_id'], 'purchase_order_id');
                $txtBox .= '<a target="_blank" title="'.$po1->getPurchaseOrderId().'" href="'. $this->getUrl('inventorysystem/purchaseorders/view', ['id' => $po1->getId()]) . '" >' . $po1->getPurchaseOrderId() . '</a> : ' . ucfirst($po1->getStatus());
                $txtBox .= '<br />';
            }
        }
        return $txtBox;
    }
}
