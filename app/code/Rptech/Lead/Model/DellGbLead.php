<?php
namespace Rptech\Lead\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class DellGbLead extends AbstractModel implements IdentityInterface
{
	const CACHE_TAG = 'rptech_dell_leads_gb';

	protected $_cacheTag = 'rptech_dell_leads_gb';

	protected $_eventPrefix = 'rptech_dell_leads_gb';

	protected function _construct()
	{
		$this->_init('Rptech\Lead\Model\ResourceModel\DellGbLead');
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


