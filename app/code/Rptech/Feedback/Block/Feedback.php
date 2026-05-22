<?php

namespace Rptech\Feedback\Block;

use Magento\Framework\View\Element\Template\Context;
use Rptech\Feedback\Model\ResourceModel\Feedback\CollectionFactory;

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
}