<?php
 
    /**
     * Webkul Grid collection
     *
     * @category    Webkul
     * @package     Webkul_Grid
     * @author      Webkul Software Private Limited
     *
     */
 
namespace Businesstobusiness\Details\Model\ResourceModel\Grid;
 
/* use required classes */
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
 
    /**
     * @param EntityFactoryInterface $entityFactory,
     * @param LoggerInterface        $logger,
     * @param FetchStrategyInterface $fetchStrategy,
     * @param ManagerInterface       $eventManager,
     * @param StoreManagerInterface  $storeManager,
     * @param AdapterInterface       $connection,
     * @param AbstractDb             $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->_init('Businesstobusiness\Details\Model\Grid', 'Businesstobusiness\Details\Model\ResourceModel\Grid');
        //Class naming structure 
        // 'NameSpace\ModuleName\Model\ModelName', 'NameSpace\ModuleName\Model\ResourceModel\ModelName'
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }
     
    protected function _initSelect()
    {
        parent::_initSelect();
 
        $this->getSelect()->joinLeft(
            ['secondTable' => $this->getTable('sales_order_item')], //2nd table name by which you want to join mail table
            'main_table.entity_id = secondTable.order_id', // common column which available in both table 
            '*' // '*' define that you want all column of 2nd table. if you want some particular column then you can define as ['column1','column2']
        );
    }
}