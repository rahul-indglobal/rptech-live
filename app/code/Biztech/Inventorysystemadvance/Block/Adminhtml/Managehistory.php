<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml;

class Managehistory extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_controller = 'adminhtml_managehistory'; /* block grid.php directory */
        $this->_blockGroup = 'Biztech_Inventorysystemadvance';
        $this->_headerText = __('Managehistory');
        $this->_addButtonLabel = __('Add New Entry');
        parent::_construct();
        $this->removeButton('add');
    }
}
