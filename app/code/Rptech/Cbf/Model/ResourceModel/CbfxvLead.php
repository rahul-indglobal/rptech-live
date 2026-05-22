<?php
namespace Rptech\Cbf\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CbfxvLead extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('rptech_cbfxv_lead', 'entity_id'); // Table name and primary key
	}
}
