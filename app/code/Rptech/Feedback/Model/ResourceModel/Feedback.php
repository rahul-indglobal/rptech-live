<?php

namespace Rptech\Feedback\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Rptech\Feedback\Api\Data\FeedbackInterface;

/**
 * Class Feedback
 * @package Rptech\Feedback\Model\ResourceModel
 */
class Feedback extends AbstractDb
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
        $this->_init(FeedbackInterface::TABLE_NAME, FeedbackInterface::KEY_ENTITY_ID);
    }
}
