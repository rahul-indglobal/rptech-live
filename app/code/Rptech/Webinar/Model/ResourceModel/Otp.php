<?php
namespace Rptech\Webinar\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Otp extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('rptech_webinar_otp', 'otp_id'); // Table name and primary key
	}
}
