<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Purchaseorders extends Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_controller = 'adminhtml_purchaseorders'; /* block grid.php directory */
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_headerText = __('Purchase Orders');
        /* Check if extension is enabled with activation key */

        $this->_addButtonLabel = __('Create PO');
        parent::_construct();
    }
}
