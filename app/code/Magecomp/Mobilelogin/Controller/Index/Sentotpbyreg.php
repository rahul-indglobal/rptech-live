<?php

namespace Magecomp\Mobilelogin\Controller\Index;

use Magecomp\Mobilelogin\Helper\Data as MagecompHelper;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

class Sentotpbyreg extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $jsonResultFactory;
    protected $session;
    protected $formKeyValidator;
    public $_storeManager;
    public $_helperdata;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $jsonResultFactory,
        Session $customerSession,
        AccountRedirect $accountRedirect,
        StoreManagerInterface $storeManager,
        MagecompHelper $helperData
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->session = $customerSession;
        $this->accountRedirect = $accountRedirect;
        $this->_storeManager = $storeManager;
        $this->_helperdata = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();

        if (array_key_exists('checkout', $data)) {
            $returnVal = $this->_helperdata->sendOTPCode($data['mobile'], $data['checkout']);
        } elseif (array_key_exists('checkoutData', $data)) {
            $returnVal = $this->_helperdata->sendOTPCode($data['mobile'], $data['checkoutData']);
        } else {
            $returnVal = $this->_helperdata->sendOTPCode($data['mobile'], 0);
        }

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($returnVal);
        return $resultJson;

    }
}
