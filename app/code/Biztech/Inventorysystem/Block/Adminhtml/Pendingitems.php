<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Pendingitems extends Container
{
    /**
     * @return Void
     */
    protected function _construct()
    {

        $this->_controller = 'adminhtml_pendingitems';
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_headerText = __('Pending Products');
                
        parent::_construct();
        $this->removeButton('add');
    }
}
