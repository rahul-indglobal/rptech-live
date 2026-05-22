<?php

namespace Rptech\CareerOpportunities\Model\ResourceModel\CareerOpportunities;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Rptech\CareerOpportunities\Model\ResourceModel\CareerOpportunities as CareerOpportunitiesResourceModel;
use Rptech\CareerOpportunities\Model\CareerOpportunities as CareerOpportunitiesModel;
use Rptech\CareerOpportunities\Api\Data\CareerOpportunitiesInterface;

/**
 * Class Collection
 * @package Rptech\CareerOpportunities\Model\ResourceModel\CareerOpportunities
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     * @codingStandardsIgnoreStart
     */
    protected $_idFieldName = CareerOpportunitiesInterface::KEY_ENTITY_ID;

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(CareerOpportunitiesModel::class, CareerOpportunitiesResourceModel::class);
    }
}
