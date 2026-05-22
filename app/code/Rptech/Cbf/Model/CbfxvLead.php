<?php
namespace Rptech\Cbf\Model;

use Magento\Framework\Model\AbstractModel;

class CbfxvLead extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(\Rptech\Cbf\Model\ResourceModel\CbfxvLead::class);
	}
}
