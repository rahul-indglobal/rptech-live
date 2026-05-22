<?php

namespace Rptech\HomepageSlider\Block;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Rptech\HomepageSlider\Model\ResourceModel\HomepageSlider\CollectionFactory;

/**
 * Class Slider
 * @package Rptech\HomepageSlider\Block
 */
class Slider extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Index constructor.
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Template\Context $context,
        array $data = []
    )
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getSliderCollection(){
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('is_active', 1)->setOrder('sort_position', 'ASC');
        return $collection;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSliderMediaPath(){
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).'homepage/images/';
    }
}