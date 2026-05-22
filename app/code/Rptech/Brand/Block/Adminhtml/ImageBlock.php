<?php

namespace Rptech\Brand\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Rptech\Brand\Model\ResourceModel\Brand\CollectionFactory as BrandCollectionFactory;
use Rptech\Brand\Model\Brand\File;

/**
 * Class ImageBlock
 * @package Rptech\Brand\Block\Adminhtml
 */
class ImageBlock extends Template
{
    protected $_template = "images.phtml";
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var BrandCollectionFactory
     */
    protected $brandCollection;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ImageBlock constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param BrandCollectionFactory $brandCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        BrandCollectionFactory $brandCollectionFactory,
        StoreManagerInterface $storeManager,
        array $data = [])
    {
        $this->coreRegistry = $registry;
        $this->brandCollection = $brandCollectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed|null
     */
    public function getBrandData(){
        return $this->coreRegistry->registry('brand');
    }

    /**
     * @param $brandEntityId
     * @return mixed
     */
    public function getBrandImagesData($brandEntityId){
        $collection = $this->brandCollection->create();
        $collection = $collection->addFieldToFilter('entity_id', $brandEntityId);
        return $collection;
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getMediaPath()
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).File::ENTITY_MEDIA_PATH;

    }
}