<?php
namespace Rptech\QuantumRma\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\QuantumRma\Model\ResourceModel\QuantumRma\CollectionFactory;

class QuantumRmaDataProvider extends AbstractDataProvider
{
	protected $loadedData;

	public function __construct(
		$name,
		$primaryFieldName,
		$requestFieldName,
		CollectionFactory $collectionFactory,
		array $meta = [],
		array $data = []
	) {
		$this->collection = $collectionFactory->create();
		parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
	}

	public function getData()
	{
		if (isset($this->loadedData)) {
			return $this->loadedData;
		}

		$items = $this->collection->getItems();
		$data = [];
		foreach ($items as $item) {
			$data[] = $item->getData();
		}

		$this->loadedData = [
			'items' => $data,
			'totalRecords' => $this->collection->getSize()
		];

		return $this->loadedData;
	}
}
