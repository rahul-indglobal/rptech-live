<?php

namespace Rptech\Event\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Rptech\Event\Api\Data\EventImageInterface;

/**
 * Class EventImage
 * @package Rptech\Event\Model\ResourceModel
 */
class EventImage extends AbstractDb
{
    /**
     * EventImage constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init(EventImageInterface::TABLE_NAME, EventImageInterface::KEY_ENTITY_ID);
    }
}