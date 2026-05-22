<?php
namespace Rptech\FinancialReport\Model\ResourceModel;


class FinancialReportDoc extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('financial_report_docs', 'entity_id');
    }

}