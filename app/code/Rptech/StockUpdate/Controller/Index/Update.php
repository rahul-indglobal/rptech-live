<?php

namespace Rptech\StockUpdate\Controller\Index;

use \Magento\Framework\Exception;
use \Magento\Framework\Exception\NoSuchEntityException;

class Update extends \Magento\Framework\App\Action\Action
{
    private $logger;
    private $session;
    protected $_productRepositoryInterface;
    protected $_stockRegistryInterface;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $session,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistryInterface,
        \Rptech\StockUpdate\Model\StockFactory $stockFactory)
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
        $this->session = $session;
        $this->_productRepositoryInterface = $productRepositoryInterface;
        $this->_stockRegistryInterface = $stockRegistryInterface;
        $this->_stockFactory = $stockFactory;
    }
    
    public function execute() {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('productstock/index/manage');

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $products = $post['product'];
            if(isset($post['run_date'])) {
                $run_date = strtotime($post['run_date']);
                $run_at = date('Y-m-d H:i:s',$run_date);
            }
            $today = date('Y-m-d H:i:s');
            $flag = $update = 0;
            foreach($products as $product) {
                $sku = $product['sku'];
                $qty = $product['qty'];
                if(isset($sku) && $sku!= "" && isset($qty) && $qty!="") {
                    $model = $this->_stockFactory->create();
                    $model->addData(['sku'=>$sku, 'qty'=>$qty,
                                    'status'=>\Rptech\StockUpdate\Model\Stock::UPDATE_STATUS_PENDING,
                                    'run_at'=>(isset($run_at)) ? $run_at : $today]);
                    $res = $model->save();
                    if(!$res) {
                        $flag = 1;
                    } else {
                        $update = 1;
                    }
                }
            }
            if($flag) {
                $this->messageManager->addError('Some error while processing');
            } else if($flag==0 && $update==1) {
                $this->messageManager->addSuccess('Record(s) saved');
            } else {
                $this->messageManager->addWarning('Did not process');
            }
        }
        return $resultRedirect;
    }
}