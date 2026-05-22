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
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\SocialLoginFree\Block\Customer\Form;

class Register extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Plumrocket\SocialLoginFree\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Register Constructor
     * @param \Plumrocket\SocialLoginFree\Helper\Data          $dataHelper
     * @param \Magento\Customer\Model\Session                  $customerSession,
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array                                            $data
     */
    public function __construct(
        \Plumrocket\SocialLoginFree\Helper\Data $dataHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return array|null
     */
    public function getPsloginData()
    {
        $data = $this->customerSession->getPsloginFields();
        $this->customerSession->unsPsloginFields();

        return $data; 
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isFakeMail($email)
    {
        return $this->dataHelper->isFakeMail($email);
    }
}
