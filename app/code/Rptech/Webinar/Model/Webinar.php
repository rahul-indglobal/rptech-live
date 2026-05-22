<?php
namespace Rptech\Webinar\Model;

use Magento\Framework\Model\AbstractModel;

class Webinar extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(\Rptech\Webinar\Model\ResourceModel\Webinar::class);
	}
}
