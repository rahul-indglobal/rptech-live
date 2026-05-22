<?php
namespace Rptech\Webinar\Model;

use Magento\Framework\Model\AbstractModel;

class Otp extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(\Rptech\Webinar\Model\ResourceModel\Otp::class);
	}
}
