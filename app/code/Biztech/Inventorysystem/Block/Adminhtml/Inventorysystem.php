<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml;

class Inventorysystem extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_inventorysystem'; /* block grid.php directory */
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_headerText = __('Manage Stock');
        
        $this->_addButtonLabel = __('Import CSV');
        $this->buttonList->add(
            'export_csv',
            [
                'label' => __('Export CSV'),
                'style' => 'background-color: #eb5202; color: #ffffff; height:45px; width:130px; font-size:16px; padding:0.5rem 0rem 0.6rem 0rem;',
                'onclick' => "setLocation('{$this->getUrl('*/*/exportCsv', array('demo_csv' => 1))}')"
            ]
        );
        parent::_construct();
    }
}
