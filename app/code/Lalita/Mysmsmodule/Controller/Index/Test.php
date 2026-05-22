<?php

namespace Lalita\Mysmsmodule\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action {

    protected $_pageFactory;
    protected $_logger;
     protected $scopeConfig;

    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory, \Psr\Log\LoggerInterface $logger,\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {
        $this->_pageFactory = $pageFactory;
        $this->_logger = $logger;
        $this->scopeConfig = $scopeConfig;
        return parent::__construct($context);
    }

    public function execute() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $variables = $objectManager->create('Magento\Variable\Model\Variable');
        $value = $variables->loadByCode('smsurl')->getPlainValue();
         echo "====>" .$smsUrl = $this->scopeConfig->getValue('section/group/smsUrl', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        echo "====>" .$smsUserName = $this->scopeConfig->getValue('section/group/smsUserName', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
          echo "====>" .$smsPassword = $this->scopeConfig->getValue('section/group/smsPassword', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        //  echo $this->scopeConfig->getValue('example_section/general/text_example', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        echo "Hello World Lalita";

        $this->_logger->debug('lalita testing for log');
        $this->_logger->info($value);

        //  echo file_get_contents("http://vas.mobilogi.com/api.php?username=rashe&password=pass1234&route=1&sender=RPTECH&mobile[]=9029398886&message=fromcode");
        // echo file_get_contents("http://vas.mobilogi.com/api.php?username=rashe&password=pass1234&route=1&sender=RPTECH&mobile[]=9029398886&message[]=fromcode");
        exit;
    }

}
