<?php
/**
 * @author Rptech
 * @package Rptech_HomepageSlider
 */
namespace Rptech\HomepageSlider\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Rptech\HomepageSlider\Setup\UpgradeSchema;

/**
 * Class HomepageSlider
 * @package Rptech\HomepageSlider\Model\ResourceModel
 */
class HomepageSlider extends AbstractDb
{
    /**
     * HomepageSlider constructor.
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
        $this->_init(UpgradeSchema::TABLE_HOMEPAGESLIDER, 'entity_id');
    }

}