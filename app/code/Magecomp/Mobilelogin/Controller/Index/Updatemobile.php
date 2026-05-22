<?php
namespace Magecomp\Mobilelogin\Controller\Index;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magecomp\Mobilelogin\Helper\Data;

class Updatemobile extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
 	protected $_helper;
    public function __construct(Context $context,PageFactory $resultPageFactory,Data $helper)
    {
        $this->_resultPageFactory = $resultPageFactory;
		$this->_helper = $helper;
		
        parent::__construct($context);
    }
    public function execute()
    {
		if($this->_helper->isEnable())
		{
        	$resultPage = $this->_resultPageFactory->create();
			$resultPage->getConfig()->getTitle()->set(__('Mobile Number Update'));

        	return $resultPage;
		}
		else
		{
			$this->_redirect('customer/account/login');
		}
    }
}