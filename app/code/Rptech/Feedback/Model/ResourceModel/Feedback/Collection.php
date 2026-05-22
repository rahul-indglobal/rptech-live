<?php

namespace Rptech\Feedback\Model\ResourceModel\Feedback;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Rptech\Feedback\Model\ResourceModel\Feedback as FeedbackResourceModel;
use Rptech\Feedback\Model\Feedback as FeedbackModel;
use Rptech\Feedback\Api\Data\FeedbackInterface;

/**
 * Class Collection
 * @package Rptech\Feedback\Model\ResourceModel\Feedback
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     * @codingStandardsIgnoreStart
     */
    protected $_idFieldName = FeedbackInterface::KEY_ENTITY_ID;

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(FeedbackModel::class, FeedbackResourceModel::class);
    }
}
