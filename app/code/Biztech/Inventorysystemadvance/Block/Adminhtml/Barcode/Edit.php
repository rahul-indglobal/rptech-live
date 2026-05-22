<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    protected $_barcodeModel;

    /**
     * @param \Magento\Backend\Block\Widget\Context         $context
     * @param \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel
    ) {
        $this->_barcodeModel = $barcodeModel;
        parent::__construct($context);
    }
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Biztech_Inventorysystemadvance';
        $this->_controller = 'adminhtml_barcode';

        parent::_construct();

        $this->buttonList->remove('save');
        $this->buttonList->add('save', array(
            'label' => __('Save Barcode'),
            'onclick' => 'submition()',
            'class' => 'action-default scalable add primary',
                ), -100);
        $this->buttonList->update('delete', 'label', __('Delete Block'));

        /* set template if new barcode create request is executed */

        if (!$this->getRequest()->getParam('id')) {
            $this->setTemplate('Biztech_Inventorysystemadvance::inventorysystemadvance/barcode/barcode.phtml');
        } else {
            $this->buttonList->remove('reset');
            $this->buttonList->remove('delete');
            $this->buttonList->remove('save');
            $barcodeData = $this->_barcodeModel->load($this->getRequest()->getParam('id'))->toArray();
            if ($barcodeData['status'] == 1) {
                $this->buttonList->add(
                    'print_barcode',
                    array(
                    'label' => __('Print'),
                    'class' => 'scalable save add primary',
                        ),
                    -100
                );
            }
        }

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('inventorysystem_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'inventorysystem_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'inventorysystem_content');
                }
            }
            
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('barcode_data')->getId()) {
            return __("View Barcode '%s'", $this->escapeHtml($this->_coreRegistry->registry('barcode_data')->getBarcode()));
        } else {
            return __('View Barcode');
        }
    }
}
