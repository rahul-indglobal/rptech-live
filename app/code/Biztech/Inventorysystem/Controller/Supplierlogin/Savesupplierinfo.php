<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierlogin;

class Savesupplierinfo extends \Magento\Framework\App\Action\Action
{
    protected $_cacheTypeList;
    protected $_cacheState;
    protected $_cacheFrontendPool;
    protected $resultPageFactory;
    protected $session;
    protected $managesupplier;
    protected $managesupplieraddress;

    /**
     * @param \Magento\Framework\App\Action\Context             $context
     * @param \Magento\Framework\App\Cache\TypeListInterface    $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface       $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool        $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Session\SessionManager         $session
     * @param \Biztech\Inventorysystem\Model\Managesupplier     $managesupplier
     * @param \Biztech\Inventorysystem\Model\Managesupplieraddr $managesupplieraddr
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Session\SessionManager  $session,
        \Biztech\Inventorysystem\Model\Managesupplier $managesupplier,
        \Biztech\Inventorysystem\Model\Managesupplieraddr $managesupplieraddr
    ) {
        $this->_cacheTypeList = $cacheTypeList;
        $this->session = $session;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->managesupplier = $managesupplier;
        $this->managesupplieraddr = $managesupplieraddr;
        //var_dump(get_class_methods($context)); exit;
        parent::__construct($context);
    }

    /**
     * This function is used for save supplier information
     * @return Void
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $data = $this->getRequest()->getPost();
            $supModel = $this->managesupplier->load($this->session->getSupplier()->getSupplierId());
            $supModel->setFirstName($data['first_name']);
            $supModel->setLastName($data['last_name']);
            $supModel->setCompany($data['company']);
            $supModel->save();
            $this->session->getSupplier()->setFirstName($data['first_name']);
            $this->session->getSupplier()->setLastName($data['last_name']);
            $this->session->getSupplier()->setCompany($data['company']);
            $supAddrModel = $this->managesupplieraddr->load($this->session->getSupplier()->getSupplierId(), 'supplier_id');
            $supAddrModel->setFirstName($data['first_name']);
            $supAddrModel->setLastName($data['last_name']);
            $supAddrModel->save();
            $this->messageManager->addSuccess(__('Account Information Updated Successfully'));
            $this->_redirect('*/*/editinfo');
        }
    }
}
