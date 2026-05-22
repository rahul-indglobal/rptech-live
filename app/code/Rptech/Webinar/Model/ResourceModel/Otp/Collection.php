<?php
namespace Rptech\Webinar\Model\ResourceModel\Otp;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(
			\Rptech\Webinar\Model\Otp::class,
			\Rptech\Webinar\Model\ResourceModel\Otp::class
		);
	}
}
