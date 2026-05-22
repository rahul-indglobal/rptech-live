<?php
namespace Rptech\QuantumRma\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class QuantumRma extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('rptech_quantumrma', 'id');
	}
}
