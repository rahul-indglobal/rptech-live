<?php
namespace Rptech\Pincode\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_curl;
	protected $_helper;
	protected $_logger;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\HTTP\Client\Curl $curl,
		\Rptech\Pincode\Helper\Data $helper,
		\Psr\Log\LoggerInterface $logger)
	{
		$this->_curl = $curl;
		$this->_helper = $helper;
		$this->_logger = $logger;
		return parent::__construct($context);
	}

	public function execute()
	{
		try{
			if(!empty($_POST['code'])){
				$pincode = $_POST['code'];
				$token = $this->_helper->getApiToken();
				$url = $this->_helper->getApiEndpoint().'?token='.$token.'&filter_codes='.$pincode;
				$this->_curl->get($url);
				$response = $this->_curl->getBody();
				$result = json_decode($response,true);
				if(!empty($result['delivery_codes'])){
					$response  = array("status"=>true, "message"=>$this->_helper->getSuccessMessage());
				}else{
					$response  = array("status"=>false, "message"=>$this->_helper->getFailedMessage());
				}
			}else{
				$response  = array("status"=>false, "message"=>$this->_helper->getFailedMessage());
			}
			$this->_logger->info('Pincode checker', $response); 
			echo json_encode($response);
		}catch(\exception $e){
			$this->_logger->error($e->getMessage()); 
		}
	}
}





