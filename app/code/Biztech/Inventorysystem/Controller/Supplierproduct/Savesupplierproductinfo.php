<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierproduct;

class Savesupplierproductinfo extends \Magento\Framework\App\Action\Action
{
    protected $_cacheTypeList;
    protected $_cacheState;
    protected $_cacheFrontendPool;
    protected $resultPageFactory;
    protected $session;
    protected $managesupplier;
    protected $managesupplieraddress;
    protected $_resourceConnection;
    protected $_productModel;
    protected $_deployConfig;

    /**
     * @param \Magento\Framework\App\Action\Context             $context
     * @param \Magento\Framework\App\Cache\TypeListInterface    $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface       $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool        $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Session\SessionManager         $session
     * @param \Biztech\Inventorysystem\Model\Managesupplier     $managesupplier
     * @param \Biztech\Inventorysystem\Model\Managesupplieraddr $managesupplieraddr
     * @param \Magento\Framework\App\ResourceConnection         $resourceConnection
     * @param \Magento\Catalog\Model\Product                    $productModel
     * @param \Magento\Framework\App\DeploymentConfig           $deployConfig
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Session\SessionManager  $session,
        \Biztech\Inventorysystem\Model\Managesupplier $managesupplier,
        \Biztech\Inventorysystem\Model\Managesupplieraddr $managesupplieraddr,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Catalog\Model\Product $productModel,
        \Magento\Framework\App\DeploymentConfig $deployConfig
    ) {
        $this->_cacheTypeList = $cacheTypeList;
        $this->session = $session;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->managesupplier = $managesupplier;
        $this->managesupplieraddr = $managesupplieraddr;
        $this->_resourceConnection = $resourceConnection;
        $this->_productModel = $productModel;
        $this->_deployConfig = $deployConfig;
        parent::__construct($context);
    }

    /**
     * This function is used for save supplier product information
     * @return Void
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired!'));
            $this->_redirect('customer/account/login');
        } else {
            $data = $this->getRequest()->getPost();
            if (!empty($data['select_product'])) {
                $resource =  $this->_resourceConnection;
                $connection = $resource->getConnection();
                $bc_suppliers = '';
                foreach ($data['select_product'] as $prodID => $selected) {
                    $product = $this->_productModel->load($prodID);
                    ;
                    $bc_suppliers = $product->getBcSupplierIs();
                    if ($data['product_status'][$prodID] == 2) {
                        $supplier_array = explode(',', $bc_suppliers);
                        $final_sup_array = array_diff($supplier_array, array($data['supplier_id']));
                        $final_sup_array_new = implode(',', $final_sup_array);
                        //var_dump($final_sup_array_new); exit;
                        if ($final_sup_array_new!='') {
                            $product->setBcSupplierIs($final_sup_array_new);
                            $product->save();
                        } else {
                            $product->setBcSupplierIs(null);
                            $product->save();
                        }
                    } else {
                        if ($bc_suppliers == null) {
                            $final_sup_array_new = $data['supplier_id'];
                        } else {
                            $supplier_arrays = explode(',', $bc_suppliers);
                            array_push($supplier_arrays, $data['supplier_id']);
                            $final_sup_array_new = implode(',', $supplier_arrays);
                        }
                        $product->setBcSupplierIs($final_sup_array_new);
                        $product->save();
                    }
                    $where = $connection->quoteInto('product_id =?', $prodID);
                    $fields = array();
                    $fields['supplier_status'] = $data['product_status'][$prodID];

                    $deploymentConfig = $this->_deployConfig;
                    $query = $connection->update($deploymentConfig->get('db/table_prefix') . 'bc_supplier_product_approve_is', $fields, $where);
                }
                $this->messageManager->addSuccess(__('Product(s) Status updated successfully!'));
            }
            $this->_redirect('*/*/viewproduct');
        }
    }
}
