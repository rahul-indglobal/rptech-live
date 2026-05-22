<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierproduct;

class Createsupplierproduct extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $session;
    protected $inventoryHelper;
    protected $product;
    protected $supplierproducttemp;
    protected $_productFactory;
    protected $_resourceConnection;

    /**
     * @param \Magento\Framework\App\Action\Context              $context
     * @param \Magento\Framework\View\Result\PageFactory         $resultPageFactory
     * @param \Magento\Framework\Session\SessionManager          $session
     * @param \Biztech\Inventorysystem\Helper\Data               $inventoryHelper
     * @param \Magento\Catalog\Model\Product                     $product
     * @param \Biztech\Inventorysystem\Model\Supplierproducttemp $supplierproducttemp
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param \Magento\Catalog\Model\ProductFactory              $productFactory
     * @param \Magento\Framework\App\ResourceConnection          $resourceConnection
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Session\SessionManager  $session,
        \Biztech\Inventorysystem\Helper\Data $inventoryHelper,
        \Magento\Catalog\Model\Product $product,
        \Biztech\Inventorysystem\Model\Supplierproducttemp $supplierproducttemp,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->storeManager     = $storeManager;
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $session;
        $this->product = $product;
        $this->supplierproducttemp = $supplierproducttemp;
        $this->inventoryHelper = $inventoryHelper;
        $this->_productFactory = $productFactory;
        $this->_resourceConnection = $resourceConnection;
        parent::__construct($context);
    }

    /**
     * This function is used for create supplier products
     * @return Void
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $postData = $this->getRequest()->getPost('product');
            $supplierID = $this->session->getSupplier()->getSupplierId();
            try {
                $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
                
                $productFactory = $this->_productFactory->create();
                if ($postData['qty'] > 0) {
                    $is_in_stock = 1;
                } else {
                    $is_in_stock = 0;
                }
                $stockData = array(
                    'manage_stock' => 1, //manage stock
                    'is_in_stock' => $is_in_stock, //Stock Availability
                    'qty' => $postData['qty'] //qty
                );
                $product = $productFactory->create();
                $product->setName($postData['product_name']);
                $product->setDescription($postData['description']);
                $product->setShortDescription($postData['short_description']);
                $product->setSku($postData['sku']);
                $product->setWeight($postData['weight']);
                $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
                $product->setVisibility($postData['visibility']);
                $product->setStockData($stockData);
                $product->setPrice((int)($postData['price']));
                $product->setCost((int) ($postData['cost']));
                $product->setTaxClassId($postData['tax_class']);
                $product->setAttributeSetId(4);
                $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
                $product->setBcSupplierIs($supplierID);
                $product->setQuantityAndStockStatus(['qty' => $postData['qty'], 'is_in_stock' => $is_in_stock]);
                $product->save();

                /* save product in temp table before admin approves it */
                $supProdTempModel = $this->supplierproducttemp->getCollection()
                        ->addFieldToFilter('supplier_id', $product->getBcSupplierIs())
                        ->addFieldToFilter('product_id', $product->getId())
                        ->getFirstItem();
                if (count($supProdTempModel) == 1) {
                    $resource =  $this->_resourceConnection;
                    $connections = $resource->getConnection();
                    $fields = array();
                    $where = $connections->quoteInto('id =?', $supProdTempModel->getId());
                    $fields['supplier_status'] = 1;
                    $fields['approve_status'] = 0;
                    $fields['new_prod_flag'] = 1;
                    $prefix = "";
                    $connections->update($prefix .'bc_supplier_product_approve_is', $fields, $where);
                }
                $this->messageManager->addSuccess(__('Product Created Successfully'));
                $this->_redirect('*/*/createproduct');
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                $this->_redirect('*/*/createproduct');
            }
        }
    }
}
