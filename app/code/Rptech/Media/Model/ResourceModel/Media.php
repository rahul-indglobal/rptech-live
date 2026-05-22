<?php
namespace Rptech\Media\Model\ResourceModel;

/**
 * Class Media
 * @package Rptech\Media\Model\ResourceModel
 */
class Media extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('media', 'entity_id');
    }

}