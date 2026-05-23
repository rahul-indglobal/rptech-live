<?php

namespace Rptech\Lead\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class DellGbLead extends AbstractDb
{

	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('rptech_dell_leads_gb10', 'entity_id');
	}

}
