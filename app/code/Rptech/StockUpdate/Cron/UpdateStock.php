<?php

namespace Rptech\StockUpdate\Cron;

class UpdateStock
{
    //protected $_eavConfig;
    protected $_logger;
    protected $_productFactory;
    protected $_productRepositoryInterface;
    protected $_stockRegistryInterface;
	protected $_storeManager;

	public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistryInterface,
        //\Magento\Eav\Model\Config $eavConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Rptech\StockUpdate\Model\StockFactory $stockFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        //$this->_eavConfig = $eavConfig;
        $this->_productFactory = $productFactory;
        $this->_productRepositoryInterface = $productRepositoryInterface;
        $this->_stockRegistryInterface = $stockRegistryInterface;
        $this->_stockFactory = $stockFactory;
        $this->_storeManager = $storeManager;
        $this->_logger = $logger;
    }

    public function run()
    {
        date_default_timezone_set('Asia/Kolkata');
    	//$this->_logger->info("RPTech - From stock update cron file - ".date('d/m/Y H:i:s'));
        $coll = $this->_stockFactory->create()->getCollection()
                ->addFieldToFilter('status',\Rptech\StockUpdate\Model\Stock::UPDATE_STATUS_PENDING);
        //$this->_logger->info("RPTech - Size - ".$coll->getSize());
        $cur_time = date('d/m/Y h:i:s');
        //$this->_logger->info("Current Date & Time - ".$cur_time."<br>");

        foreach ($coll as $key => $value) {
            //print_r($value->getData());
            $data = $value->getData();
            $sch_time = date('d/m/Y h:i:s', strtotime($data['run_at']));
            //echo "<br>".$data['row_id'].".....".$sch_time.".....";

            $failed_skus = array();
            if($sch_time <= $cur_time)
            {
                $rid = $data['row_id'];
                $sku = $data['sku'];
                $qty = $data['qty'];
                $model = $this->_stockFactory->create();
                $model->load($rid);
    			try {
                    $stockItem = $this->_stockRegistryInterface->getStockItemBySku($sku);
                    $pid = $stockItem->getProductId();
                    $stockItem->setData('qty',$qty);
                    $stockItem->setIsInStock((bool)$qty);
                    $stockItem->save();
                    $product = $this->_productRepositoryInterface->getById($pid);
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $productObj = $objectManager->create('\Magento\Catalog\Model\Product');
                    $prod = $productObj->loadByAttribute('sku', $sku);
                    if($qty > 0) {
                        $product->setSellStatus(1)->save();
                        $prod->setSellStatus(1)->save();
                    } else {
                        $product->setSellStatus(0)->save();
                        $prod->setSellStatus(0)->save();
                    }
                    $model->setStatus(\Rptech\StockUpdate\Model\Stock::UPDATE_STATUS_UPDATED)->save();
                } catch (\Exception $e) {
                    $model->setStatus(\Rptech\StockUpdate\Model\Stock::UPDATE_STATUS_ERROR)->save();
                    $failed_skus[]=$sku;
                }
            }

            if(count($failed_skus)) {
                //$this->messageManager->addError('Some error processing SKUs - '.implode(", ", $failed_skus));
            } else {
                //$this->messageManager->addSuccess('Record(s) updated');
            }
        }
    	return;
    }
}