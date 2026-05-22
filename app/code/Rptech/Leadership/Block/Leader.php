<?php

namespace Rptech\Leadership\Block;

use Magento\Framework\View\Element\Template\Context;
use Rptech\Leadership\Model\ResourceModel\Leadership\Collection;
use Rptech\Leadership\Model\ResourceModel\Leadership\CollectionFactory as LeadershipCollectionFactory;

/**
 * Class Leader
 * @package Rptech\Leadership\Block
 */
class Leader extends \Magento\Framework\View\Element\Template
{
	const SENIOR_MANAGMENT = 'senior_management';
	const BOARD_DIRECTORS = 'directors';
	
    /**
     * @var LeadershipCollectionFactory
     */
    protected $leadershipCollectionFactory;

    public function __construct(
        Context $context,
        LeadershipCollectionFactory $leadershipCollectionFactory,
        array $data = []
    ) {
        $this->leadershipCollectionFactory = $leadershipCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get leadership collection
     *
     * @return Collection
     */
    public function getLeadershipCollection()
    {
        return $this->leadershipCollectionFactory->create();
    }

	public function getSeniorManagementLeadershipCollection()
	{
		return $this->leadershipCollectionFactory->create()->addFieldToFilter('type', self::SENIOR_MANAGMENT);
	}

	public function getDirectorsLeadershipCollection()
	{
		return $this->leadershipCollectionFactory->create()->addFieldToFilter('type', self::BOARD_DIRECTORS);
	}
}