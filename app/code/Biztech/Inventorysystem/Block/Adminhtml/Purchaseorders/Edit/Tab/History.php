<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Edit\Tab;

use Magento\Backend\Block\Template;
use Biztech\Inventorysystem\Model\Purchaseorders;
use Magento\Backend\Block\Template\Context;

class History extends Template
{
    protected $poModel;

    /**
     * @param Context        $context
     * @param Purchaseorders $poModel
     */
    public function __construct(
        Context $context,
        Purchaseorders $poModel
    ) {
        parent::__construct($context);
        $this->poModel = $poModel;
    }

    /**
     * Prepare layout
     * @return Void
     */
    protected function _prepareLayout()
    {
        $onclick = "submitAndReloadArea($('po_history_block').parentNode, '" . $this->getSubmitUrl() . "')";
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            ['label' => __('Submit Comment'), 'class' => 'action-save action-secondary', 'onclick' => $onclick]
        );
        $this->setChild('submit_button', $button);
        return parent::_prepareLayout();
    }

    /**
     * PO increment id
     * @param  int $poID
     * @return int
     */
    public function getPOIncrementID($poID)
    {
        $po = $this->poModel->load($poID);
        return $po->getPurchaseOrderId();
    }

    /**
     * PO url
     * @return String
     */
    public function getSubmitUrl()
    {
        $poID = $this->getRequest()->getParam('po_id') ? $this->getRequest()->getParam('po_id') : $this->getRequest()->getParam('id');
        $incrementID = $this->getPOIncrementID($poID);
        return $this->getUrl('inventorysystem/purchaseorders/UpdatePOComment', ['po_id' => $poID, 'po_increment' => $incrementID]);
    }

    /**
     * Purchase order details
     * @return Object
     */
    public function getPurchaseOrder()
    {
        return $this->poModel;
    }
}
