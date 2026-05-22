<?php

namespace Rptech\ServiceAddress\Block;

use Magento\Framework\View\Element\Template\Context;
use Rptech\ServiceAddress\Model\ResourceModel\Address\CollectionFactory;

class ServiceAddress extends \Magento\Framework\View\Element\Template
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
     * Get city collection
     *
     * @return \Rptech\ServiceAddress\Model\ResourceModel\Address\Collection
     */
    public function getCityCollection()
    {
        /**
         * @var \Rptech\ServiceAddress\Model\ResourceModel\Address\Collection $collection
         */
        $collection = $this->collectionFactory->create();
        $collection->setOrder("city_name", "ASC");
        return $collection;
    }
}