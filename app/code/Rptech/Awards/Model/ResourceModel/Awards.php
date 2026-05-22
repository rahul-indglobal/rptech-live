<?php
/**
 * @author Rptech
 * @package Rptech_Awards
 */
namespace Rptech\Awards\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Rptech\Awards\Setup\UpgradeSchema;

/**
 * Class Awards
 * @package Rptech\Awards\Model\ResourceModel
 */
class Awards extends AbstractDb
{
    /**
     * Awards constructor.
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
        $this->_init(UpgradeSchema::TABLE_AWARD, 'entity_id');
    }

}