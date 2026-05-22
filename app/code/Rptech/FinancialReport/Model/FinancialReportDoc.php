<?php
namespace Rptech\FinancialReport\Model;

class FinancialReportDoc extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'financial_report_docs';

    protected $_cacheTag = 'financial_report_docs';

    protected $_eventPrefix = 'financial_report_docs';

    protected function _construct()
    {
        $this->_init('Rptech\FinancialReport\Model\ResourceModel\FinancialReportDoc');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}