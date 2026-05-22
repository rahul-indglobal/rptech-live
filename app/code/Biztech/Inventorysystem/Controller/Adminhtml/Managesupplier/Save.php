<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Managesupplier;

use Biztech\Inventorysystem\Model\Managesupplier;
use Biztech\Inventorysystem\Model\Managesupplieraddr;
use Magento\Backend\App\Action;
use Magento\Backend\Helper\Js;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Save extends Action
{

    protected $date;
    protected $_supplierModel;
    protected $_supplierAddrModel;
    protected $_jsHelper;
    protected $_resources;
    protected $_productModel;
    protected $_eavAttribute;
    protected $_backendSession;

    /**
     * @param Context $context
     * @param DateTime $date
     * @param Managesupplier $managesupplier
     * @param Managesupplieraddr $managesupplieraddr
     * @param Js $jsHelper
     * @param Product $productModel
     * @param ResourceConnection $resources
     */
    public function __construct(
        Context $context,
        DateTime $date,
        Managesupplier $managesupplier,
        Managesupplieraddr $managesupplieraddr,
        Js $jsHelper,
        Product $productModel,
        Attribute $eavAttribute,
        ResourceConnection $resources
    ) {
        $this->date = $date;
        parent::__construct($context);
        $this->_supplierModel = $managesupplier;
        $this->_jsHelper = $jsHelper;
        $this->_supplierAddrModel = $managesupplieraddr;
        $this->_productModel = $productModel;
        $this->_eavAttribute = $eavAttribute;
        $this->_resources = $resources;
        $this->_backendSession = $context->getSession();
    }

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($postData) {
            $id = $this->getRequest()->getParam('id') ? $this->getRequest()->getParam('id') : $this->getRequest()->getParam('supplier_id');
            if ($id) {
                $this->_supplierModel->load($id);
                $suppAccFields = $this->_supplierModel->getData();
            }

            if ($id && isset($postData['new_password']) && !empty($postData['new_password'])) {
                $a['password'] = md5($postData['new_password']);
                $postData = array_merge($a, $postData);
                unset($postData['new_password']);
            } elseif ($id) {
                unset($postData['password']);
                unset($postData['new_password']);
            } else {
                $a['password'] = md5($postData['password']);
                unset($postData['password']);
                $postData = array_merge($a, $postData);
            }

            $this->_supplierModel->setData($postData);
            $this->_supplierAddrModel->setData($postData);

            try {
                if (isset($postData['products'])) {
                    $productIds = $this->_jsHelper->decodeGridSerializedInput($postData['products']);
                    $this->_supplierModel->setProductsData($productIds);
                } else {
                    $this->_supplierModel->setProductsData(null);
                }
                $this->_supplierModel->save();
                $this->saveSuppliers($this->_supplierModel, $postData);
                $this->saveProducts($this->_supplierModel, $postData);

                $this->_supplierAddrModel->setSupplierId($this->_supplierModel->getId());

                if (!empty($this->_supplierModel->getSupplierAddress($this->_supplierModel))) {
                    $addressID = $this->_supplierModel->getSupplierAddress($this->_supplierModel)[0];
                    $this->_supplierAddrModel->setSupplierAddressId($addressID);
                }
                $this->_supplierAddrModel->save();

                $this->messageManager->addSuccess(__('Supplier Saved Successfully!'));
                $this->_backendSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['supplier_id' => $this->_supplierModel->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $parts = explode(",", $e->getMessage());
                if (substr($parts[0], -6, -1) == "email") {
                    $this->messageManager->addError("Duplicate Entry: Supplier Email already exist");
                } else {
                    $this->messageManager->addError($e->getMessage());
                }
                $this->_getSession()->setFormData($postData);
                unset($postData['status']);
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }

            $this->_getSession()->setFormData($postData);
            return $resultRedirect->setPath('*/*/edit', ['supplier_id' => $this->getRequest()->getParam('supplier_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $supplierModel
     * @param $post
     */
    private function saveSuppliers($supplierModel, $post)
    {

        if (isset($post['products'])) {
            $_finalSupplier = [];

            $productIds = $this->_jsHelper->decodeGridSerializedInput($post['products']);
            $_supplierProducts = (array) $supplierModel->getProducts($supplierModel);

            if (!is_array($_supplierProducts)) {
                $_supplierProducts = [];
            }

            $deleteSupplierFromProduct = array_diff($_supplierProducts, $productIds);

            if (!empty($deleteSupplierFromProduct)) {
                $productIds = array_merge($productIds, $deleteSupplierFromProduct);
            }

            try {
                foreach ($productIds as $productId) {
                    $productM = $this->_productModel->load($productId);
                    $oldSupplierId = explode(',', $productM->getBcSupplierIs());

                    $newSupplierId = (array) $supplierModel->getId();

                    $insert = array_diff($newSupplierId, $oldSupplierId);

                    if (in_array($productId, $deleteSupplierFromProduct)) {
                        if (($key = array_search($supplierModel->getId(), $oldSupplierId)) !== false) {
                            unset($oldSupplierId[$key]);
                        }
                        if (!empty($oldSupplierId)) {
                            $updateIds = implode(',', array_filter($oldSupplierId));
                            $_finalSupplier[$productId] = $updateIds;
                        } else {
                            $productM->setBcSupplierIs(null);
                            $_finalSupplier[$productId] = null;
                        }
                    }


                    if (!empty($insert)) {
                        $updateIds = array_merge($oldSupplierId, $insert);
                        $updateIds = implode(',', array_filter($updateIds));
                        $_finalSupplier[$productId] = $updateIds;
                    }
                }

                foreach ($_finalSupplier as $productId => $supplierId) {
                    $this->updateProductSuppliers($supplierId, $productId);
                }
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the supplier.'));
            }
        }
    }

    /**
     * @param $supplierId
     * @param $productId
     */
    protected function updateProductSuppliers($supplierId, $productId)
    {

        $connection = $this->_resources->getConnection();
        $attributeId = $this->_eavAttribute->getIdByCode('catalog_product', 'bc_supplier_is');
        $table = $this->_resources->getTableName('catalog_product_entity_varchar');
        
        try {
            $connection->insertOnDuplicate(
                $table,
                [
                'attribute_id' => $attributeId,
                'store_id' => 0,
                'entity_id' => $productId,
                'value' => $supplierId
                    ]
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }

    /**
     * @param $model
     * @param $post
     */
    private function saveProducts($model, $post)
    {
        
        if (isset($post['products'])) {
            $productIds = $this->_jsHelper->decodeGridSerializedInput($post['products']);
            $model->setProductsData($productIds);

            try {
                $oldProducts = (array) $model->getProducts($model);
                $newProducts = (array) $productIds;

                $connection = $this->_resources->getConnection();

                $table = $this->_resources->getTableName(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplier::TBL_ATT_PRODUCT);
                $approveTable = $this->_resources->getTableName(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplier::TBL_ATT_PRODUCT_APPROVE);
                $insert = array_diff($newProducts, $oldProducts);
                $delete = array_diff($oldProducts, $newProducts);
               
                if ($delete) {
                    $where = ['supplier_id = ?' => (int) $model->getId(), 'product_id IN (?)' => $delete];
                    $connection->delete($table, $where);
                   
                    //delete data in bc_supplier_product_approve_is
                    $connection->delete($approveTable, $where);
                }

                if ($insert) {
                    $postData = [];
                    foreach ($insert as $product_id) {
                        $postData[] = ['supplier_id' => (int) $model->getId(), 'product_id' => (int) $product_id];
                    }
                    $connection->insertMultiple($table, $postData);

                    //insert data in bc_supplier_product_approve_is
                    foreach ($insert as $product_id) {
                        $approveData[] = [
                            'supplier_id' => (int) $model->getId(),
                            'product_id' => (int) $product_id,
                            'approve_status' => '1',
                            'new_prod_flag'  => '0',
                            'supplier_status' => (int) $post['is_active']
                            ];
                    }
                    
                    $connection->insertMultiple($approveTable, $approveData);
                }
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the supplier.'));
            }
        }
    }
}
