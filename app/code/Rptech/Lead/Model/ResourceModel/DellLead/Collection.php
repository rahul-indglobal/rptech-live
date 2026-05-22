<?php

namespace Rptech\Lead\Model\ResourceModel\DellLead;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'lead_id';
	protected $_eventPrefix = 'rptech_dell_lead_collection';
	protected $_eventObject = 'lead_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Rptech\Lead\Model\DellLead', 'Rptech\Lead\Model\ResourceModel\DellLead');
	}

}

