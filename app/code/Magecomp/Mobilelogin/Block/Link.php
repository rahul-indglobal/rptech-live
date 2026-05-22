<?php
namespace Magecomp\Mobilelogin\Block;

use Magecomp\Mobilelogin\Helper\Data;
use Magento\Framework\View\Element\Template\Context;

class Link extends \Magento\Framework\View\Element\Template
{
    protected $_helper;

    public function __construct(Context $context, Data $helper)
    {
        $this->_helper = $helper;
        parent::__construct($context);
    }
    protected function _toHtml()
    {
        if($this->_helper->isEnable())
        {
            if($this->_request->getModuleName()=="mobilelogin")
            {
                $html = '<li class="nav item current">';
                $html .= '<strong>'. $this->escapeHtml($this->getLabel()).'</strong>';
                $html .= '</li>';
            }
            else
            {
                $html = "<li class='nav item'>";
                $html .= '<a href=' . $this->getUrl($this->getPath()) . ' >' . $this->escapeHtml($this->getLabel()) . '</a>';
                $html .= '</li>';
            }
            return $html;
        }

    }
}