<?php

namespace Rptech\Brand\Block\Brand;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Rptech\Brand\Model\ResourceModel\Brand\CollectionFactory as BrandCollectionFactory;
use Rptech\Brand\Model\Brand\File;

/**
 * Class Index
 * @package Rptech\Brand\Block\Brand
 */
class Index extends Template
{
    /**
     * @var BrandCollectionFactory
     */
    protected $brandCollection;

    public function __construct
    (
        BrandCollectionFactory $brandCollectionFactory,
        Template\Context $context,
        array $data = []
    )
    {
        $this->brandCollection = $brandCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getBrands () {
        /**
         * @var \Rptech\Brand\Model\ResourceModel\Brand\Collection $collection
         */
        $collection = $this->brandCollection->create();
        $collection->addFieldToFilter('is_active', 1)->setOrder('title', 'ASC');
        return $collection;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBrandMediaUrl(){
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).File::ENTITY_MEDIA_PATH;
    }
}