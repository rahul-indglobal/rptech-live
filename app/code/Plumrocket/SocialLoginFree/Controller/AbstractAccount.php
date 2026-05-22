<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_SocialLoginFree
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\SocialLoginFree\Controller;

abstract class AbstractAccount extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Plumrocket\SocialLoginFree\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Store\Model\StoreManager
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\View\Layout\Interceptor
     */
    protected $layout;

    /**
     * AbstractAccount constructor.
     *
     * @param \Magento\Framework\App\Action\Context   $context
     * @param \Magento\Customer\Model\Session         $customerSession
     * @param \Plumrocket\SocialLoginFree\Helper\Data $dataHelper
     * @param \Magento\Store\Model\StoreManager       $storeManager
     * @param \Magento\Framework\View\Layout\Interceptor $layout
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Plumrocket\SocialLoginFree\Helper\Data $dataHelper,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\View\Layout\Interceptor $layout
    ) {
        parent::__construct($context);
        $this->customerSession  = $customerSession;
        $this->dataHelper       = $dataHelper;
        $this->storeManager     = $storeManager;
        $this->layout           = $layout;
    }

    protected function _windowClose()
    {
        if($this->getRequest()->isXmlHttpRequest()) {
            $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
            $this->getResponse()->setBody(json_encode([
                'windowClose' => true
            ]));
        }else{
            $this->getResponse()->setBody($this->_jsWrap('window.close();'));
        }
    }

    /**
     * @param $customer
     */
    protected function _dispatchRegisterSuccess($customer)
    {
        $this->_eventManager->dispatch(
            'customer_register_success',
            ['account_controller' => $this, 'customer' => $customer]
        );
    }

    /**
     * @return \Magento\Customer\Model\Session
     */
    protected function _getSession()
    {
        return $this->customerSession;
    }

    /**
     * @param       $url
     * @param array $params
     *
     * @return string
     */
    protected function _getUrl($url, $params = [])
    {
        return $this->_url->getUrl($url, $params);
    }

    /**
     * @return \Plumrocket\SocialLoginFree\Helper\Data
     */
    protected function _getHelper()
    {
        return $this->dataHelper;
    }

    protected function _jsWrap($js)
    {
        return '<html><head></head><body><script type="text/javascript">'.$js.'</script></body></html>';
    }

    /**
     * Display error in debug mode
     * @param \Plumrocket\SocialLoginPro\Model\Account $model
     * @return $this
     */
    protected function displayError($model)
    {
        $errorBlock = $this->layout->getBlockSingleton('Magento\Framework\View\Element\Template')
            ->setTemplate('Plumrocket_SocialLoginFree::error.phtml')
            ->setError($model->getDebugErrors())
            ->toHtml();

        $this->getResponse()->setBody($errorBlock);
        return $this;
    }
}
