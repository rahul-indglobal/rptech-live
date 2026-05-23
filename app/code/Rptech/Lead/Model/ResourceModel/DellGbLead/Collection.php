<?php

namespace Rptech\Lead\Model\ResourceModel\DellGbLead;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'rptech_dell_gb_lead_collection';
	protected $_eventObject = 'dell_gb_lead_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Rptech\Lead\Model\DellGbLead', 'Rptech\Lead\Model\ResourceModel\DellGbLead');
	}

}

