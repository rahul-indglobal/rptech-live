<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block;

class Header extends \Magento\Framework\View\Element\Html\Link
{
    protected $_template = 'Biztech_Inventorysystem::link.phtml';
    
    /**
     * Logout url
     * @return String
     */
    public function getLogoutUrl()
    {
        return __('inventorysystem/supplierlogin/supplierlogout');
    }
    
    /**
     * URL href link
     * @return String
     */
    public function getHref()
    {
        return __('inventorysystem/supplierlogin/supplierlogout');
    }

    /**
     * Label
     * @return String
     */
    public function getLabel()
    {
        return __('Logout');
    }
}
