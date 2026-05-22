<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml;

class Purchaseinvoice extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        
        $this->_controller = 'adminhtml_purchaseinvoice';/*block grid.php directory*/
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_headerText = __('Purchaseinvoice');
        parent::_construct();
        $this->removeButton('add');
    }
}
