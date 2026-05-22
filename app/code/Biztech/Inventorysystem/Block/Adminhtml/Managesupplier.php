<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Managesupplier extends Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_controller = 'adminhtml_managesupplier'; /* block grid.php directory */
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_headerText = __('Managesupplier');
        
        $this->_addButtonLabel = __('Add Supplier');
        parent::_construct();
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
