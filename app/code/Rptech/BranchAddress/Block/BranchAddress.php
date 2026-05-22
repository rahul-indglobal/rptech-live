<?php

namespace Rptech\BranchAddress\Block;

use Magento\Framework\View\Element\Template\Context;
use Rptech\BranchAddress\Model\ResourceModel\BranchAddress\CollectionFactory;
use Rptech\BranchAddress\Api\Data\BranchAddressInterface;

class BranchAddress extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * ServiceAddress constructor.
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get branch location collection
     *
     * @return \Rptech\ServiceAddress\Model\ResourceModel\Address\Collection
     */
    public function getBranchCollection()
    {
        /**
         * @var \Rptech\ServiceAddress\Model\ResourceModel\Address\Collection $collection
         */
        $collection = $this->collectionFactory->create();
        $collection->setOrder(BranchAddressInterface::KEY_BRANCH_LOCATION, "ASC");
        return $collection;
    }
}