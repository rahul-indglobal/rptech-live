<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Supplierlogin;

class Savesupplieraddrinfo extends \Magento\Framework\App\Action\Action
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
     * This function is used for save supplier address information
     * @return Void
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $data = $this->getRequest()->getPost();
            $this->session->getSupplierAddress()->setAddressLine1($data['street_addr']);
            $this->session->getSupplierAddress()->setCity($data['city']);
            $this->session->getSupplierAddress()->setCountry($data['country']);
            $this->session->getSupplierAddress()->setState($data['state']);
            $this->session->getSupplierAddress()->setStateId($data['state_id']);
            $this->session->getSupplierAddress()->setPostalCode($data['postal_code']);
            $this->session->getSupplierAddress()->setTelephone($data['telephone']);
            
            $supAddrModel = $this->managesupplieraddr->load($this->session->getSupplier()->getSupplierId(), 'supplier_id');
            $supAddrModel->setFirstName($data['first_name']);
            $supAddrModel->setLastName($data['last_name']);
            $supAddrModel->setAddressLine1($data['street_addr']);
            $supAddrModel->setCity($data['city']);
            $supAddrModel->setCountry($data['country']);
            $supAddrModel->setState($data['state']);
            $supAddrModel->setStateId($data['state_id']);
            $supAddrModel->setPostalCode($data['postal_code']);
            $supAddrModel->setTelephone($data['telephone']);
            $supAddrModel->save();
            $this->messageManager->addSuccess(__('Address Information Updated Successfully'));
            $this->_redirect('*/*/editaddrinfo');
        }
    }
}
