<?php
namespace Rptech\QuantumRma\Model;

use Magento\Framework\Model\AbstractModel;

class QuantumRma extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(\Rptech\QuantumRma\Model\ResourceModel\QuantumRma::class);
	}
}
