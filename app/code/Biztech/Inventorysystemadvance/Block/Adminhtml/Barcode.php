<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml;

class Barcode extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_controller = 'adminhtml_barcode'; /* block grid.php directory */
        $this->_blockGroup = 'Biztech_Inventorysystemadvance';
        $this->_headerText = __('Barcode');
        $this->buttonList->add('createBarcodeBtn', array(
            'label' => __('Create Barcode'),
            'onclick' => "setLocation('" . $this->getUrl('*/*/createBarcode') . "')",
            'class' => 'add primary'
        ));

        $this->buttonList->add('scanBarcodeBtn', array(
            'label' => __('Scan Barcode'),
            'onclick' => "setLocation('" . $this->getUrl('*/*/scanBarcode') . "')",
            'class' => 'save'
        ));
        parent::_construct();
        $this->removeButton('add');
    }
}
