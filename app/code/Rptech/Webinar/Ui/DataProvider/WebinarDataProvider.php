<?php
namespace Rptech\Webinar\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\Webinar\Model\ResourceModel\Webinar\CollectionFactory;

class WebinarDataProvider extends AbstractDataProvider
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
