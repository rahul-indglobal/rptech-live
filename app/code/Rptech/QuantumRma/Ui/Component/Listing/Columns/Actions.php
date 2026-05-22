<?php
namespace Rptech\QuantumRma\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Actions column for admin grid
 */
class Actions extends Column
{
	/** @var UrlInterface */
	protected $urlBuilder;

	/** Edit route (controller path) */
	const URL_PATH_EDIT = 'quantum_support/quantumrma/edit';

	/** Delete route (controller path) */
	const URL_PATH_DELETE = 'quantum_support/quantumrma/delete';

	public function __construct(
		UrlInterface $urlBuilder,
		\Magento\Framework\View\Element\UiComponent\ContextInterface $context,
		\Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
		array $components = [],
		array $data = []
	) {
		$this->urlBuilder = $urlBuilder;
		parent::__construct($context, $uiComponentFactory, $components, $data);
	}

	/**
	 * Add actions (Edit/Delete) to each row in grid
	 */
	public function prepareDataSource(array $dataSource)
	{
		if (isset($dataSource['data']['items'])) {
			$indexField = $this->getData('config/indexField') ?: 'id';

			foreach ($dataSource['data']['items'] as & $item) {
				if (!empty($item[$indexField])) {
					$item[$this->getData('name')] = [
						'edit' => [
							'href' => $this->urlBuilder->getUrl(
								self::URL_PATH_EDIT,
								['id' => $item[$indexField]]
							),
							'label' => __('Edit'),
						]
					];
				}
			}
		}

		return $dataSource;
	}
}
