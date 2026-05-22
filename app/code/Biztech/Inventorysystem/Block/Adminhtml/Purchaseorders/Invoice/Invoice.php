<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Invoice;

use Magento\Backend\Block\Widget\Form\Container;

class Invoice extends Container
{

    protected $_purchaseordersModel;
    protected $_purchaseordersitemsModel;
    protected $_currentStore;
    protected $_supplierModel;
    protected $_currencyInterface;

    /**
     * @param \Magento\Backend\Block\Widget\Context              $context
     * @param \Biztech\Inventorysystem\Model\Purchaseorders      $purchaseordersModel
     * @param \Biztech\Inventorysystem\Model\Purchaseordersitems $purchaseordersitemsModel
     * @param \Magento\Store\Model\Store                         $currentStore
     * @param \Biztech\Inventorysystem\Model\Managesupplier      $supplierModel
     * @param \Magento\Framework\Locale\CurrencyInterface        $currencyInterface
     * @param array                                              $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Biztech\Inventorysystem\Model\Purchaseorders $purchaseordersModel,
        \Biztech\Inventorysystem\Model\Purchaseordersitems $purchaseordersitemsModel,
        \Magento\Store\Model\Store $currentStore,
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        array $data = []
    ) {

        $this->_purchaseordersModel = $purchaseordersModel;
        $this->_purchaseordersitemsModel = $purchaseordersitemsModel;
        $this->_currentStore = $currentStore;
        $this->_supplierModel = $supplierModel;
        $this->_currencyInterface = $currencyInterface;
        parent::__construct($context, $data);
    }

    /**
     * @return Void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Biztech_Inventorysystem';
        $this->_controller = 'adminhtml_purchaseorders';

        parent::_construct();
        $this->buttonList->remove('reset');
        $this->buttonList->remove('save');
        $this->buttonList->remove('back');
        $this->buttonList->add(
            'back',
            array(
            'label' => __('Back'),
            'class' => 'back',
            'onclick' => "setLocation('" . $this->getUrl('inventorysystem/purchaseorders/view', ['id' => $this->getRequest()->getParam('porder_id')]) . "')",
                ),
            -100
        );
        $this->setTemplate('Biztech_Inventorysystem::purchaseorders/invoice.phtml');

        $this->_formScripts[] = "
        function toggleEditor() {
            if (tinyMCE.getInstanceById('inventorysystem_content') == null) {
                tinyMCE.execCommand('mceAddControl', false, 'inventorysystem_content');
            } else {
                tinyMCE.execCommand('mceRemoveControl', false, 'inventorysystem_content');
            }
        }
        ";
    }

    /**
     * Get PO
     * @return Object
     */
    public function getPO()
    {
        return $this->_purchaseordersModel;
    }

    /**
     * Get PO items
     * @return Object
     */
    public function getPOItems()
    {
        return $this->_purchaseordersitemsModel->getCollection();
    }

    /**
     * Get current store
     * @return Object
     */
    public function getCurrentStore()
    {
        return $this->_currentStore;
    }

    /**
     * Get supplier
     * @return Object
     */
    public function getSupplier()
    {
        return $this->_supplierModel;
    }

    /**
     * Get currency interface
     * @return Object
     */
    public function getCurrencyInterface()
    {
        return $this->_currencyInterface;
    }
}
