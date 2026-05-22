<?php

namespace Rptech\CareerOpportunities\Block;

use Magento\Framework\View\Element\Template\Context;
use Rptech\CareerOpportunities\Model\ResourceModel\CareerOpportunities\CollectionFactory;

class CareerOpportunities extends \Magento\Framework\View\Element\Template
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
}