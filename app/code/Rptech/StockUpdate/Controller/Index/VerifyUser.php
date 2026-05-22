<?php

namespace Rptech\StockUpdate\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class VerifyUser extends \Magento\Framework\App\Action\Action
{
    private $_dateTime;
    private $_helper;
    private $_helperdata;
    private $_modelLoginOtpFactory;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Rptech\General\Helper\Data $dataHelper,
        \Magecomp\Mobilelogin\Helper\Data $helperData,
        \Magecomp\Mobilelogin\Model\LoginotpmodelFactory $modelLoginOtpFactory
    )
    {
        $this->_dateTime = $dateTime;
        $this->_helper = $dataHelper;
        $this->_helperdata = $helperData;
        $this->_modelLoginOtpFactory = $modelLoginOtpFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $mobile = $this->getRequest()->get('mobile');
        $mobiles = array();
        for($j=1; $j<=3; $j++) {
            $config_name = 'rpt_general/stock/emp_num_'.$j;
            if($this->_helper->getConfig($config_name)!=null && $this->_helper->getConfig($config_name)!=0) {
                $mobiles[] = $this->_helper->getConfig($config_name);
            }
        }

        if (in_array($mobile, $mobiles)) {
            $data = "true";
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($data);
            $return = $this->sendStockAdminOTP($mobile);
            $resultJson->setData($return);
            return $resultJson;
        } else {
            $data = "Invalid user";
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($data);
            return $resultJson;
        }
    }

    private function sendStockAdminOTP($mobile)
    {
        try
        {
            $otpModels = $this->_modelLoginOtpFactory->create();
            $collection = $otpModels->getCollection();
            $collection->addFieldToFilter('mobile', $mobile);
            $date = $this->_dateTime->gmtDate();
            $randomCode = $this->_helperdata->generateRandomString();
            $message = $this->_helperdata->getLoginOtpMessage($mobile, $randomCode);

            if (count($collection) == 0) {

                $otpModel = $this->_modelLoginOtpFactory->create();
                $otpModel->setRandomCode($randomCode);
                $otpModel->setCreatedTime($date);
                $otpModel->setIsVerify(0);
                $otpModel->setMobile($mobile);
                $otpModel->save();
            } else {

                $otpModel = $this->_modelLoginOtpFactory->create()->load($mobile, 'mobile');
                $otpModel->setRandomCode($randomCode);
                $otpModel->setCreatedTime($date);
                $otpModel->setIsVerify(0);
                $otpModel->setMobile($mobile);
                $otpModel->save();
            }
            $apiReturn = $this->_helperdata->curlApiCall($message, $mobile);
            return $apiReturn;
        } catch (\Exception $e) {
            return "false";
        }
    }
}