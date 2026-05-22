<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Managehistory\Renderer;

class Orderlink extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $_orderModel;
    protected $_warehouseModel;

    /**
     * @param \Magento\Backend\Block\Context                  $context
     * @param \Magento\Sales\Model\Order                      $orderModel
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Sales\Model\Order $orderModel,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
    ) {

        $this->_orderModel = $orderModel;
        $this->_warehouseModel = $warehouseModel;
        parent::__construct($context);
    }

    /**
     * This function is used for the generate the order links
     * @param  \Magento\Framework\DataObject $row
     * @return mixed
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        if ($this->getColumn()->getIndex() == 'order') {
            $txtbox = '';
            if ($row->getSystemAction() == "Order Created" || $row->getSystemAction() == "Order Canceled" || $row->getSystemAction() == "Credit Memo") {
                $orderId = $this->_orderModel->loadByIncrementId($row->getOrder())->getEntityId();
                $txtbox .= "<a href='" . $this->getUrl('sales/order/view', array('order_id' => $orderId)) . "'>" . $row->getOrder() . "</a>";
            } else if ($row->getSystemAction() == "Warehouse Transaction") {
                $warehouseID = $this->_warehouseModel->load($row->getOrder(), 'warehouse_name')->getId();
                $txtbox .= "<a target='_blank' href='" . $this->getUrl('inventorysystemadvance/warehouse/edit', array('id' => $warehouseID)) . "'>" . $row->getOrder() . "</a>";
            } else {
                $txtbox .= "<span>" . $row->getOrder() . "</span>";
            }
        }
        return $txtbox;
    }
}
