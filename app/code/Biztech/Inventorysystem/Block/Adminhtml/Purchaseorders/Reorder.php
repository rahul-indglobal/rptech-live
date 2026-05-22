<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders;

use Magento\Backend\Block\Widget\Context;
use Biztech\Inventorysystem\Model\Purchaseorders;

class Reorder extends \Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\Edit\Tab\View
{
   
    protected $_pricingHelper;

    /**
     * Supplier details
     * @param  int $productID
     * @return String
     */
    public function getSupplierHtml($productID)
    {
        $supplierHtml = '';
        $connection = $this->_bizHelper->getResource()->getConnection();
        $getSuppConfig = $this->_bizHelper->getConfig('inventorysystem/supplierconfig/showsupplier');
        $productCollection = $this->getProductModelFactory()->create()->getCollection();
        $productCollection->addAttributeToFilter('entity_id', $productID);

        $productCollection->getSelect()->join(
            ['sup_prod' => $this->_bizHelper->getResource()->getTableName(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplier::TBL_ATT_PRODUCT)],
            'e.entity_id=sup_prod.product_id',
            ['GROUP_concat(DISTINCT(supplier_id)) AS supplier_id']
        );
        $productCollection->getSelect()->group('e.entity_id');
        $productsData = $productCollection->getData();
        
        if (empty($productsData)) {
            $supplierCollection = $this->getSuppliers()->getCollection()
            ->addFieldToFilter('is_active', 1)
            ->setOrder('first_name', 'ASC');
            $supplierData = $supplierCollection->getData();
            $supplierHtml = $this->_bizHelper->getSupplierName($supplierData);
        } else {
            if ($getSuppConfig == 1) {
                $supplierCollection = $this->getSuppliers()->getCollection()
                ->addFieldToFilter('is_active', 1);
            } else {
                $supplierCollection = $this->getSuppliers()->getCollection()
                ->addFieldToFilter('is_active', 1)
                ->addFieldToFilter('supplier_id', ['in' => explode(',', $productsData[0]['supplier_id'])]);
            }
            $supplierData = $supplierCollection->getData();
            $supplierHtml = $this->_bizHelper->getSupplierName($supplierData, $productsData[0]['supplier_id']);
        }

        return $supplierHtml;
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

        $this->buttonList->remove('save');
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
        $this->buttonList->remove('stock_received');
        $this->buttonList->remove('reorder');
        $this->buttonList->remove('po_invoice');
        $this->buttonList->remove('print');

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
    public function getPricingHelper()
    {
        return $this->_pricingHelper;
    }
}
