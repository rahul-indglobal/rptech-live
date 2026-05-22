<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Pendingorders extends Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_controller = 'adminhtml_pendingorders'; /* block grid.php directory */
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_headerText = __('Pending Orders');
        
        parent::_construct();
        $this->removeButton('add');
    }
}
