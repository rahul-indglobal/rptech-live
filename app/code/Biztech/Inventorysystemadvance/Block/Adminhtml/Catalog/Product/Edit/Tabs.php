<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Catalog\Product\Edit;

use Biztech\Inventorysystemadvance\Model\ResourceModel\Warehouse\Collection as WarehouseCollection;
use Biztech\Inventorysystemadvance\Model\Warehouse;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Field;

class Tabs extends AbstractModifier
{

    const WAREHOUSE_DATA_FIELDS = 'bc_warehouse_product';

    protected $meta = [];
    protected $locator;
    protected $warehouse;

    /**
     * @param ArrayManager        $arrayManager
     * @param WarehouseCollection $collectionFactory
     * @param Warehouse           $warehouse
     * @param LocatorInterface    $locator
     */
    public function __construct(
        ArrayManager $arrayManager,
        WarehouseCollection $collectionFactory,
        Warehouse $warehouse,
        LocatorInterface $locator
    ) {
        $this->arrayManager = $arrayManager;
        $this->_collectionFactory = $collectionFactory;
        $this->locator = $locator;
        $this->warehouse = $warehouse;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $this->meta = $this->customiseCustomAttrField();

        return $this->meta;
    }

    /**
     * Customise Custom Attribute field
     *
     * @param array $meta
     *
     * @return array
     */
    protected function customiseCustomAttrField()
    {
        $fieldCode = 'bc_warehouse_product'; //your custom attribute code
        $elementPath = $this->arrayManager->findPath($fieldCode, $this->meta, null, 'children');
        if (!$elementPath) {
            return $this->meta;
        }
        $this->meta = $this->arrayManager->merge(
            $elementPath,
            $this->meta,
            [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'dynamicRows',
                        'renderDefaultRecord' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => '',
                        'dndConfig' => [
                            'enabled' => false,
                        ],
                        'disabled' => false,
                        'sortOrder' =>
                        $this->arrayManager->get($elementPath . '/arguments/data/config/sortOrder', $this->meta),
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => [
                        'warehouse_name' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Select::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'dataScope' => 'warehouse_name',
                                        'label' => __('Name'),
                                        'options' => $this->getWarehouses(),
                                    ],
                                ],
                            ],
                        ],
                        'warehouse_qty' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Number::NAME,
                                        'label' => __('Qty'),
                                        'dataScope' => 'warehouse_qty',
                                    ],
                                ],
                            ],
                        ],
                        'actionDelete' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => 'actionDelete',
                                        'dataType' => Text::NAME,
                                        'label' => '',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
                ]
        );
        $this->meta = $this->arrayManager->set(
            $this->arrayManager->slicePath($elementPath, 0, -3)
                . '/' . $fieldCode,
            $this->meta,
            $this->arrayManager->get($elementPath, $this->meta)
        );
        $this->meta = $this->arrayManager->remove(
            $this->arrayManager->slicePath($elementPath, 0, -2),
            $this->meta
        );

        return $this->meta;
    }

    /**
     * Warehouse details
     * @return Object
     */
    protected function getWarehouses()
    {
        $warehousesGroups = [];
        $this->_collectionFactory->addFieldToFilter('status', 1);
        $getWarehouses = $this->_collectionFactory->load();

        if ($getWarehouses && !empty($getWarehouses)) {
            foreach ($getWarehouses as $getWarehouse) {
                $warehousesGroups[] = [
                    'label' => $getWarehouse['warehouse_name'],
                    'value' => $getWarehouse['id'],
                ];
            }
        }
        return $warehousesGroups;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $fieldCode = 'bc_warehouse_product';
        $warehouseData = [];
        $model = $this->locator->getProduct();
        $modelId = $model->getId();

        $warehouseDetails = $this->warehouse->getDetailsFromProduct($modelId);

        if (!empty($warehouseDetails)) {
            foreach ($warehouseDetails as $key => $warehouseInfo) {
                $warehouseData[$key]['warehouse_name'] = $warehouseInfo['warehouse_id'];
                $warehouseData[$key]['warehouse_qty'] = $warehouseInfo['quantity'];
            }
            $path = $modelId . '/' . self::DATA_SOURCE_DEFAULT . '/' . $fieldCode;
            $data = $this->arrayManager->set($path, $data, $warehouseData);
        }


        return $data;
    }
}
