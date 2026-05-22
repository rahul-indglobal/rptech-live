<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Backend\Block\Widget\Context;

class Salesorderid extends AbstractRenderer
{
    protected $_orderModel;
    protected $_storeManager;
    protected $_urlInterface;

    /**
     * @param \Magento\Sales\Model\Order                 $orderModel
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface            $urlInterface
     */
    public function __construct(
        \Magento\Sales\Model\Order $orderModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface
    ) {

        $this->_orderModel = $orderModel;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
    }
    
    /**
     * Sales order link
     * @param  DataObject $row
     * @return String
     */
    public function render(DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex()=='sales_order_id') {
            $sOrderIncrId = $row->getSalesOrderId();
            if ($sOrderIncrId) {
                $getIncrIds = explode(",", $sOrderIncrId);
                // var_dump($sOrderIncrId);
                for ($i=0; $i<count($getIncrIds); $i++) {
                    $orderModel = $this->_orderModel;
                    $order = $orderModel->loadByIncrementId($getIncrIds[$i]);
                    $id    = $order->getId();
                    $setLinks[] = "<a title='".$getIncrIds[$i]."' href='".$this->_urlInterface->getUrl('sales/order/view', ['order_id'=>$id])."'>".$getIncrIds[$i]."</a>";
                }
                $txtbox = "<span>".implode(",", $setLinks)."</span>";
            }
        }
        return $txtbox;
    }
}
