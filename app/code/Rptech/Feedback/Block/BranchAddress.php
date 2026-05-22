<?php

namespace Rptech\Feedback\Block;

use Magento\Framework\View\Element\Template\Context;
use Rptech\Feedback\Model\ResourceModel\Feedback\CollectionFactory;
use Rptech\Feedback\Api\Data\FeedbackInterface;

class Feedback extends \Magento\Framework\View\Element\Template
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
        $collection->setOrder(FeedbackInterface::KEY_BRANCH_LOCATION, "ASC");
        return $collection;
    }
}