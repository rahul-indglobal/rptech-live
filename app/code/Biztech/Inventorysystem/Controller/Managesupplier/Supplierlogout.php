<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Managesupplier;

class Supplierlogout extends \Magento\Framework\App\Action\Action
{
    protected $_cacheTypeList;
    protected $_cacheState;
    protected $_cacheFrontendPool;
    protected $resultPageFactory;
    protected $session;

    /**
     * @param \Magento\Framework\App\Action\Context          $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface    $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool     $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory     $resultPageFactory
     * @param \Magento\Framework\Session\SessionManager      $session
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Session\SessionManager  $session
    ) {
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $session;
        parent::__construct($context);
    }

    /**
     * Supplier logout
     * @return Void
     */
    public function execute()
    {
        if (!$this->session->getSupplier()) {
            $this->messageManager->addError(__('Your session has been expired.'));
            $this->_redirect('customer/account/login');
        } else {
            $this->session->unsSupplier();
            $this->session->unsSupplierAddress();
            $this->messageManager->addSuccess(__('You are signed out'));
            $this->_redirect('customer/account/login');
        }
    }
}
