<?php
namespace Rptech\Cbf\Model\ResourceModel\CbfxvLead;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(\Rptech\Cbf\Model\CbfxvLead::class, \Rptech\Cbf\Model\ResourceModel\CbfxvLead::class);
	}
}
