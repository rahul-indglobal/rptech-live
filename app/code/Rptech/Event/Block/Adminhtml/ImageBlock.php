<?php

namespace Rptech\Event\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Rptech\Event\Model\ResourceModel\EventImage\CollectionFactory as EventImageCollectionFactory;

/**
 * Class ImageBlock
 * @package Rptech\Event\Block\Adminhtml
 */
class ImageBlock extends Template
{
    const EVENT_IMAGE_PATH = "event/images/";
    protected $_template = "images.phtml";
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var EventImageCollectionFactory
     */
    protected $eventImageCollection;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ImageBlock constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param EventImageCollectionFactory $eventImageCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        EventImageCollectionFactory $eventImageCollectionFactory,
        StoreManagerInterface $storeManager,
        array $data = [])
    {
        $this->coreRegistry = $registry;
        $this->eventImageCollection = $eventImageCollectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed|null
     */
    public function getEventData(){
        return $this->coreRegistry->registry('event');
    }

    /**
     * @param $eventEntityId
     * @return mixed
     */
    public function getEventImagesData($eventEntityId){
        $collection = $this->eventImageCollection->create();
        $collection = $collection->addFieldToFilter('event_entity_id', $eventEntityId);
        return $collection;
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getMediaPath()
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).self::EVENT_IMAGE_PATH;

    }
}