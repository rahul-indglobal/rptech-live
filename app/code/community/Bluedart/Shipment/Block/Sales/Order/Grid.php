<?php
	class Bluedart_Shipment_Block_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
	{
		protected function _prepareMassaction()
		{
			$this->getMassactionBlock()->addItem('export_for_bluedart', array(
			'label'=> Mage::helper('sales')->__('Export for Blue Dart'),
			'url'  => $this->getUrl('admin_shipment/adminhtml_waybillmass/orderexportbluedart'),
			));
			parent::_prepareMassaction();
		}
	}
?>
