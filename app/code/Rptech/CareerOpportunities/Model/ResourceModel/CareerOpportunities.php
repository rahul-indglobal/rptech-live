<?php

namespace Rptech\CareerOpportunities\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Rptech\CareerOpportunities\Api\Data\CareerOpportunitiesInterface;

/**
 * Class CareerOpportunities
 * @package Rptech\CareerOpportunities\Model\ResourceModel
 */
class CareerOpportunities extends AbstractDb
{
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param DateTime $date
     */
    public function __construct(
        Context $context,
        DateTime $date
    ) {
        $this->date = $date;
        parent::__construct($context);
    }

    /**
     * Resource initialisation
     *
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(CareerOpportunitiesInterface::TABLE_NAME, CareerOpportunitiesInterface::KEY_ENTITY_ID);
    }
}
