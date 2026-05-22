<?php

namespace Rptech\BranchAddress\Model\ResourceModel\BranchAddress;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Rptech\BranchAddress\Model\ResourceModel\BranchAddress as BranchAddressResourceModel;
use Rptech\BranchAddress\Model\BranchAddress as BranchAddressModel;
use Rptech\BranchAddress\Api\Data\BranchAddressInterface;

class Collection extends AbstractCollection
{
    /**
     * @var string
     * @codingStandardsIgnoreStart
     */
    protected $_idFieldName = BranchAddressInterface::KEY_ENTITY_ID;

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(BranchAddressModel::class, BranchAddressResourceModel::class);
    }
}
