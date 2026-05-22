<?php

namespace Rptech\Event\Block\Event;

use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Rptech\Event\Model\ResourceModel\EventImage\Collection;
use Rptech\Event\Model\ResourceModel\EventImage\CollectionFactory;

/**
 * Class DetailsBlock
 * @package Rptech\Event\Block\Event
 */
class DetailsBlock extends Template
{
    const EVENT_MEDIA_PATH = "event/images/";

    /**
     * @var Registry
     */
    protected $coreRegistry;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * DetailsBlock constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->coreRegistry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Get event data
     * @return \Rptech\Event\Model\Event
     */
    public function getCurrentEvent(){
        return $this->coreRegistry->registry('event');
    }

    /**
     * Get event image collection
     *
     * @param $eventId
     * @return Collection
     */
    public function getEventImageCollection($eventId)
    {
        /**
         * @var Collection $collection
         */
        $collection = $this->collectionFactory->create();
        $collection = $collection->addFieldToFilter('event_entity_id', $eventId);
        return $collection;
    }

    /**
     * Get event media url
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getEventMediaUrl(){
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).self::EVENT_MEDIA_PATH;
    }
}