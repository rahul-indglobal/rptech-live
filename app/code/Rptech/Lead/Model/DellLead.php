<?php


namespace Rptech\Lead\Model;

class DellLead extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'rptech_dell_lead';

	protected $_cacheTag = 'rptech_dell_lead';

	protected $_eventPrefix = 'rptech_dell_lead';

	protected function _construct()
	{
		$this->_init('Rptech\Lead\Model\ResourceModel\DellLead');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}


