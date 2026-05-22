<?php
namespace Rptech\FinancialReport\Model\ResourceModel\FinancialReportDoc;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'financial_report_doc_collection';
    protected $_eventObject = 'report_doc_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\FinancialReport\Model\FinancialReportDoc', 'Rptech\FinancialReport\Model\ResourceModel\FinancialReportDoc');
    }

}