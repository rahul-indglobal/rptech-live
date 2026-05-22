<?php

namespace Rptech\Announcement\Block;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Rptech\Announcement\Model\ResourceModel\Announcement\CollectionFactory;
use Rptech\Announcement\Model\Announcement\File;

/**
 * Class Index
 * @package Rptech\Announcement\Block
 */
class Index extends Template
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
    public function getAnnouncementCollection(){
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('is_active', 1)->setOrder('sort_position', 'ASC');
        return $collection;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAnnounceMediaPath(){
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).'announcement/docs';
    }
}