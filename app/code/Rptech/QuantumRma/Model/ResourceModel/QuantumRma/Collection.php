<?php
namespace Rptech\QuantumRma\Model\ResourceModel\QuantumRma;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(\Rptech\QuantumRma\Model\QuantumRma::class, \Rptech\QuantumRma\Model\ResourceModel\QuantumRma::class);
	}
}
