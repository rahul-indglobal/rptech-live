<?php
/**
 * @author Rptech
 * @package Rptech_Announcement
 */
namespace Rptech\Announcement\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Rptech\Announcement\Setup\UpgradeSchema;

/**
 * Class Announcement
 * @package Rptech\Announcement\Model\ResourceModel
 */
class Announcement extends AbstractDb
{
    /**
     * Announcement constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init(UpgradeSchema::TABLE_ANNOUNCEMENT, 'entity_id');
    }

}