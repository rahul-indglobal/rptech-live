<?php

namespace LalitaRajput\AdminSalesOrderViewButton\Plugin;
use Magento\Framework\UrlInterface; 
class PluginBeforeView
{

    protected $urlBuider;
    public function __construct(\Magento\Framework\UrlInterface $urlBuilder){
         $this->urlBuilder = $urlBuilder;
    }
    public function beforeGetOrderId(\Magento\Sales\Block\Adminhtml\Order\View $subject){
        $url = $this->urlBuilder->getUrl('b2b/grid/', $paramsHere = array());
        $subject->addButton(
                'reset33',
                ['label' => __('B2B Order Dashboard'), 'onclick' => "setLocation('$url')", 'class' => 'reset'],
                -1
            );

        return null;
    }
}