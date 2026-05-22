<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action\Context;
use Biztech\Inventorysystemadvance\Helper\Data;

class Save extends \Magento\Backend\App\Action
{

    protected $_jsHelper;
    protected $_inventorysystemadvanceHelper;
    protected $_warehouseModel;
    protected $_resourceConnection;
    protected $_backendSession;
    
    /**
     * @param Context                                         $context
     * @param \Magento\Backend\Helper\Js                      $jsHelper
     * @param Data                                            $inventorysystemadvanceHelper
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     * @param \Magento\Framework\App\ResourceConnection       $resourceConnection
     */
    public function __construct(
        Context $context,
        \Magento\Backend\Helper\Js $jsHelper,
        Data $inventorysystemadvanceHelper,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->_jsHelper = $jsHelper;
        $this->_inventorysystemadvanceHelper = $inventorysystemadvanceHelper;
        $this->_warehouseModel = $warehouseModel;
        $this->_resourceConnection = $resourceConnection;
        $this->_backendSession = $context->getSession();
        parent::__construct($context);
    }

    /**
     * This function is used for save the warehouse
     * @return void
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->_warehouseModel;

            $curWarehouseID = $this->getRequest()->getParam('id');

            if ($curWarehouseID) {
                $model->load($curWarehouseID);
            }
            $model->setData($data);
            if (isset($data['state']) && $data['state'] != null) {
                $model->setState($data['state']);
            }

            try {
                $products = $this->getRequest()->getPost('products', -1);
                $productsDataArray = $this->_jsHelper->decodeGridSerializedInput($products);
                $productsDataArray = array_flip($productsDataArray);
                if ($curWarehouseID) {
                    $this->_resources = $this->_resourceConnection;
                    $connection = $this->_resources->getConnection();
                    $tableName = $this->_resources->getTableName('bc_warehouse_product_is');
                    $get_ware_prod_sql = $connection->select()
                            ->from($tableName, ['rel_id', 'product_id', 'quantity'])
                            ->where('warehouse_id = ' . $curWarehouseID);
                    $get_ware_prod = $connection->fetchAll($get_ware_prod_sql);
                    $all_prod_ids = [];
                    for ($i = 0; $i < count($get_ware_prod); $i++) {
                        $all_prod_ids[$get_ware_prod[$i]['product_id']] = $get_ware_prod[$i]['quantity'];
                    }
                    $removed_product = array_diff_key($all_prod_ids, $productsDataArray);
                } else {
                    $removed_product = '';
                }
                $model->save();

                if ($model->getId()) {
                    $curWarehouseID = $model->getId();
                }
                $paramsArr = [];
                if ($products != -1 && $data['status'] == 1) {
                    if (is_array($data['product_qty']) && !empty($data['product_qty'])) {
                        foreach ($productsDataArray as $prodID => $value) {
                            if (isset($data['product_qty'][$prodID])) {
                                $paramsArr[] = ['id' => $prodID,
                                    'data' => ['total_qty' => $data['product_qty'][$prodID]['qty'],
                                        'qty_level' => $data['product_qty'][$prodID]['qty_level'],
                                        'comment' => '']];
                            }
                        }
                        $returnValue = $this->_inventorysystemadvanceHelper->updateInventory($paramsArr, $curWarehouseID, $removed_product, 'warehouse');
                        if ($returnValue === false) {
                            $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                            return;
                        }
                    }
                }

                $this->messageManager->addSuccess(__('Warehouse Saved Successfully'));
                $this->_backendSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $parts = explode(",", $e->getMessage());
                if (substr($parts[0], -15, -1) == "warehouse_name") {
                    $this->messageManager->addError("Duplicate Entry: Warehouse Name you entered already exist.");
                } else {
                    $this->messageManager->addError($e->getMessage());
                }
                unset($data['status']);
                $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                return;
            }
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                return;
            }
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', ['banner_id' => $this->getRequest()->getParam('banner_id')]);
            return;
        }
        $this->_redirect('*/*/');
    }
}
