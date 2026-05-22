<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Managesupplier;

use Magento\Framework\Session\SessionManager;
use Biztech\Inventorysystem\Model\Managesupplier;
use Biztech\Inventorysystem\Model\Managesupplieraddr;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\Action\Context;

class Login extends \Magento\Framework\App\Action\Action
{

    protected $session;
    protected $manageSupplier;
    protected $manageSupplierAddr;
    protected $formKeyValidator;
    
    /**
     * @param Context            $context            [description]
     * @param SessionManager     $supplierSession    [description]
     * @param Managesupplier     $manageSupplier     [description]
     * @param Managesupplieraddr $manageSupplierAddr [description]
     * @param Validator          $formKeyValidator   [description]
     */
    public function __construct(
        Context $context,
        SessionManager $supplierSession,
        Managesupplier $manageSupplier,
        Managesupplieraddr $manageSupplierAddr,
        Validator $formKeyValidator
    ) {
        $this->session = $supplierSession;
        $this->manageSupplier = $manageSupplier;
        $this->manageSupplierAddr = $manageSupplierAddr;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct($context);
    }

    /**
     * Login page of the supplier
     * @return Void
     */
    public function execute()
    {
        $session = $this->session;
        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $isSupplier = $this->manageSupplier->confirmSupplierDetails($login);
                    if (is_array($isSupplier) && isset($isSupplier['response'])) {
                        $error = __($isSupplier['response']);
                        throw new \Exception($error, 1);
                    } else if (!$isSupplier) {
                        $error = __('Email id does not exists for Supplier!');
                        throw new \Exception($error, 1);
                    } else {
                        $session->setSupplier($isSupplier);
                        $suppAddr = $this->manageSupplierAddr->load($this->manageSupplier->getSupplierAddress($isSupplier));
                        $session->setSupplierAddress($suppAddr);
                        $this->_redirect('*/*/supplierdashboard');
                    }
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    $this->messageManager->addError(__($message));
                    $session->setUsername($login['username']);
                    $this->_redirect('customer/account/login');
                }
            } else {
                $this->messageManager->addError('Login and password are required.');
            }
        }
    }
}
