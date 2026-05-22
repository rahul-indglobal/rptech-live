<?php

namespace Rptech\Media\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Rptech\Brand\Model\ResourceModel\Brand\CollectionFactory as BrandCollectionFactory;

/**
 * Class Data
 * @package Rptech\Media\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var BrandCollectionFactory
     */
    protected $brandCollectionFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param BrandCollectionFactory $brandCollectionFactory
     */
    public function __construct
    (
        Context $context,
        BrandCollectionFactory $brandCollectionFactory
    )
    {
        $this->brandCollectionFactory = $brandCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @return BrandCollectionFactory
     */
    public function getBrandCollection(){
        return $this->brandCollectionFactory->create();
    }
}