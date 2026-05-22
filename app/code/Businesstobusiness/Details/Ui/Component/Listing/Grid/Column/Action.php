<?php
/**
 * Grid Ui Component Action.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Businesstobusiness\Details\Ui\Component\Listing\Grid\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Action extends Column
{
    /** Url path */
    const ROW_EDIT_URL = 'grid/grid/addrow';
    /** @var UrlInterface */
    protected $_urlBuilder;

    /**
     * @var string
     */
    private $_editUrl;

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
        array $components = [],
        array $data = [],
        $editUrl = self::ROW_EDIT_URL
    ) {
        $this->_urlBuilder = $urlBuilder;
        $this->_editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();


        if (isset($dataSource['data']['items'])) {
            $i=0;
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    if($item['customer_group'] == 4){
                            //$order_id[] = $item['entity_id'];

                            $today_order_sql = "select name,qty_ordered from sales_order_item where order_id = ".$item['entity_id'];
                            $today_order_result = $connection->fetchRow($today_order_sql);
                            $dataSource['data']['items'][$i]["product_name"] = $today_order_result["name"];
                            $dataSource['data']['items'][$i]["quantity"] = number_format($today_order_result["qty_ordered"]);
                            $dataSource['data']['items'][$i]["grand_total"] = number_format($dataSource['data']['items'][$i]["grand_total"], 2, '.', '');

                            $item[$name]['edit'] = [
                            'href' => $this->_urlBuilder->getUrl(
                                $this->_editUrl,
                                ['id' => $item['entity_id']]
                            ),
                            'label' => __('View'),
                        ];
                    }else{
                        unset($dataSource['data']['items'][$i]);
                    } 
                }
                $i++;
            }
        }

        $dataSource['data']['items'] = array_values($dataSource['data']['items']);
        $dataSource['data']['totalRecords'] = count($dataSource['data']['items']);

        return $dataSource;
    }
}
