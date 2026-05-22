<?php

/**
 * Grid Ui Component Action.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace LalitaRajput\B2B\Ui\Component\Listing\Grid\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Action extends Column {

	/** Url path */
	const ROW_EDIT_URL = 'b2b/grid/addrow';

	/** @var UrlInterface */
	protected $_urlBuilder;

	/**
	 * @var string
	 */
	private $_editUrl;

	/** @var CollectionFactory */
	protected $_orderCollectionFactory;

	/** @var \Magento\Sales\Model\ResourceModel\Order\Collection */
	protected $orders;

	/**
	 * @param ContextInterface   $context
	 * @param UiComponentFactory $uiComponentFactory
	 * @param UrlInterface       $urlBuilder
	 * @param array              $components
	 * @param array              $data
	 * @param string             $editUrl
	 */
	public function __construct(
	    ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        CollectionFactory $orderCollectionFactory,
        $editUrl = self::ROW_EDIT_URL,
        array $components = [],
        array $data = []
	) {
		$this->_urlBuilder = $urlBuilder;
		$this->_editUrl = $editUrl;
		$this->_orderCollectionFactory = $orderCollectionFactory;
		parent::__construct($context, $uiComponentFactory, $components, $data);
	}

	/**
	 * Prepare Data Source.
	 *
	 * @param array $dataSource
	 *
	 * @return array
	 */
	public function prepareDataSource(array $dataSource) {
		$collection = $this->_orderCollectionFactory->create()
			->addAttributeToSelect('*')
			->addFieldToFilter('customer_group_id', array('eq' => 4)); //Add condition if you wish



		$joinConditions = 's.increment_id = main_table.increment_id';

		$collection->getSelect()->join(
			['s' => $collection->getTable('sales_order_grid')], $joinConditions, ['*']
		);
		$orders = $collection->getData();

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();

		if (!empty($orders)) {
			$i = 0;
			foreach ($orders as &$item) {
				$name = $this->getData('name');
				$today_order_sql = "select name,qty_ordered from sales_order_item where order_id = " . $item['entity_id'];
				$today_order_result = $connection->fetchRow($today_order_sql);
				$item["product_name"] = $today_order_result["name"];
				$item["quantity"] = number_format($today_order_result["qty_ordered"]);
				$item["grand_total"] = number_format($dataSource['data']['items'][$i]["grand_total"], 2, '.', '');

				$item[$name]['view'] = [
					'href' => $this->_urlBuilder->getUrl(
						'sales/order/view/', ['order_id' => $item['entity_id'], 'type' => "b2b"]
					),
					'label' => __('View'),
				];
				$i++;
			}
		}

//		print_r($orders);


//		if (isset($dataSource['data']['items'])) {
//			$i = 0;
//			foreach ($dataSource['data']['items'] as &$item) {
//				$name = $this->getData('name');
//				if (isset($item['entity_id'])) {
//					if ($item['customer_group'] == 4) {
//						//$order_id[] = $item['entity_id'];
//
//						$today_order_sql = "select name,qty_ordered from sales_order_item where order_id = " . $item['entity_id'];
//						$today_order_result = $connection->fetchRow($today_order_sql);
//						$dataSource['data']['items'][$i]["product_name"] = $today_order_result["name"];
//						$dataSource['data']['items'][$i]["quantity"] = number_format($today_order_result["qty_ordered"]);
//						$dataSource['data']['items'][$i]["grand_total"] = number_format($dataSource['data']['items'][$i]["grand_total"], 2, '.', '');
//
//						$item[$name]['view'] = [
//							'href' => $this->_urlBuilder->getUrl(
//								'sales/order/view/', ['order_id' => $item['entity_id'], 'type' => "b2b"]
//							),
//							'label' => __('View'),
//						];
//					} else {
//						unset($dataSource['data']['items'][$i]);
//					}
//				}
//				$i++;
//			}
//		}

//		$dataSource['data']['items'] = array_values($dataSource['data']['items']);
//		$dataSource['data']['totalRecords'] = count($dataSource['data']['items']);

		$dataSource['data']['items'] = array_values($orders);
		$dataSource['data']['totalRecords'] = count($orders);
//		print_r($dataSource);
//		die;
		return $dataSource;
	}

}
