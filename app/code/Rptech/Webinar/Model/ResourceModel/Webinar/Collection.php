<?php
namespace Rptech\Webinar\Model\ResourceModel\Webinar;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(\Rptech\Webinar\Model\Webinar::class, \Rptech\Webinar\Model\ResourceModel\Webinar::class);
	}
}
