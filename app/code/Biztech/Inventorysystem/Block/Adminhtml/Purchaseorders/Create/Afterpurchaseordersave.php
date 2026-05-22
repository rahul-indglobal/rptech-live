<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Create;

use Biztech\Inventorysystem\Helper\Data as BizHelper;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Biztech\Inventorysystem\Model\ManagesupplierFactory;

class Afterpurchaseordersave extends Container
{

    protected $_supplierModelFactory;
    protected $_bizHelper;
    protected $_pricingHelper;
    protected $_currentStore;
    protected $_currencyInterface;
    protected $_warehouseModel;

    /**
     * @param Context                                         $context
     * @param ManagesupplierFactory                           $supplierModelFactory
     * @param BizHelper                                       $bizHelper
     * @param \Magento\Framework\Pricing\Helper\Data          $pricingHelper
     * @param \Magento\Store\Model\Store                      $currentStore
     * @param \Magento\Framework\Locale\CurrencyInterface     $currencyInterface
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     */
    public function __construct(
        Context $context,
        ManagesupplierFactory $supplierModelFactory,
        BizHelper $bizHelper,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Store\Model\Store $currentStore,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
    ) {
        parent::__construct($context);
        $this->_supplierModelFactory = $supplierModelFactory;
        $this->_bizHelper = $bizHelper;
        $this->_pricingHelper = $pricingHelper;
        $this->_currentStore = $currentStore;
        $this->_currencyInterface = $currencyInterface;
        $this->_warehouseModel = $warehouseModel;
    }

    /**
     * Supplier fullname
     * @param  int $supID
     * @return String
     */
    public function getSupplierFullName($supID)
    {
        $supDetails = $this->getSupplierDetails($supID);
        return $supDetails->getFirstName() . ' ' . $supDetails->getLastName();
    }

    /**
     * Shipping method
     * @param  int $supID
     * @return String
     */
    public function getShippingMethod($supID)
    {
        $supDetails = $this->getSupplierDetails($supID);
        return $supDetails->getShipmentMethod();
    }

    /**
     * Payment method
     * @param  int $supID
     * @return String
     */
    public function getPaymentMethod($supID)
    {
        $supDetails = $this->getSupplierDetails($supID);
        return $supDetails->getPaymentMethod();
    }

    /**
     * Supplier details
     * @param  int $supID
     * @return Object
     */
    protected function getSupplierDetails($supID)
    {
        return $this->getSuppliers()->load($supID);
    }

    /**
     * Supplier details
     * @return Object
     */
    protected function getSuppliers()
    {
        return $this->_supplierModelFactory->create();
    }

    /**
     * Warehouse name
     * @param  int $warehouseId
     * @return String
     */
    protected function getWarehouseName($warehouseId)
    {
        $whDetails = $this->getWarehouseDetails($warehouseId);
        return $whDetails->getWarehouseName();
    }

    /**
     * Warehouse details
     * @param  int $warehouseId
     * @return Object
     */
    protected function getWarehouseDetails($warehouseId)
    {
        return $this->getWarehouse()->load($warehouseId);
    }

    /**
     * Warehouse details
     * @param  int $id
     * @return Object
     */
    public function getWarehouse($id)
    {
        return $this->_warehouseModel->load($id);
    }

    /**
     * Biztech helper
     * @return Object
     */
    public function getBizHelper()
    {
        return $this->_bizHelper;
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

        // $this->buttonList->remove('save');
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');

        $this->buttonList->update('save', 'label', __('Submit'));
        $this->setTemplate('Biztech_Inventorysystem::purchaseorders/aftersavepo.phtml');


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
     * Pricing helper
     * @return Object
     */
    public function getPriceHelper()
    {
        return $this->_pricingHelper;
    }

    /**
     * Currency code
     * @return Object
     */
    public function getCurrencyCode()
    {
        return $this->_currentStore->getCurrentCurrencyCode();
    }

    /**
     * Currency interface
     * @return Object
     */
    public function getCurrencyInterface()
    {
        return $this->_currencyInterface;
    }
}
