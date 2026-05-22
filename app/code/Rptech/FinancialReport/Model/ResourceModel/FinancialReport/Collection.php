<?php
namespace Rptech\FinancialReport\Model\ResourceModel\FinancialReport;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'financial_report_collection';
    protected $_eventObject = 'report_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rptech\FinancialReport\Model\FinancialReport', 'Rptech\FinancialReport\Model\ResourceModel\FinancialReport');
    }

    /**
     * Retrieve child items associated with the parent item
     *
     * @param \Rptech\FinancialReport\Model\FinancialReport $object
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getChildItems(\Rptech\FinancialReport\Model\FinancialReport $object)
    {
        $select = $this->getConnection()->select()
            ->from($this->getTable('financial_report_docs'), ['entity_id', 'document', 'parent_id'])
            ->where('parent_id = ?', $object->getId());

        return $this->getConnection()->fetchAll($select);
    }

}