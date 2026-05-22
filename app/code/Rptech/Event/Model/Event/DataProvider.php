<?php

namespace Rptech\Event\Model\Event;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\Event\Model\Event;
use Rptech\Event\Model\EventFactory;
use Rptech\Event\Model\Event\File;
use Rptech\Event\Model\ResourceModel\Event\CollectionFactory;

/**
 * Class DataProvider
 * @package Rptech\Event\Model\Event
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var \Rptech\Event\Model\Event\File
     */
    protected $fileInfo;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var EventFactory
     */
    protected $eventFactory;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Rptech\Event\Model\Event\File $fileInfo
     * @param RequestInterface $request
     * @param EventFactory $eventFactory
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Rptech\Event\Model\Event\File $fileInfo,
        RequestInterface $request,
        EventFactory $eventFactory,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->fileInfo = $fileInfo;
        $this->request = $request;
        $this->eventFactory = $eventFactory;
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var Event $event */
        foreach ($items as $event) {
            $eventData = $event->getData();
            $this->loadedData[$event->getId()] = $eventData;
        }

        $data = $this->dataPersistor->get('event');
        if (!empty($data)) {
            $event = $this->collection->getNewEmptyItem();
            $event->setData($data);
            $this->loadedData[$event->getId()] = $event->getData();
            $this->dataPersistor->clear('event');
        }
        return $this->loadedData;
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return [];
    }

    /**
     * @param $leadership
     * @param $leadershipData
     * @return mixed
     */
    private function convertValues($event, $eventData)
    {
        $fileName = $event->getData('image');
        $fileInfo = $this->getFileInfo();
        if ($fileName && $fileInfo->isFile($fileName)) {
            $stat = $fileInfo->getStat($fileName);
            $mime = $fileInfo->getMimeType($fileName);
            unset($eventData['image']);
            $leadershipData['image'][0]['name'] = basename($fileName);
            $leadershipData['image'][0]['url'] = $event->getImageUrl();
            $leadershipData['image'][0]['size'] = isset($stat) ? $stat['size'] : 0;
            $leadershipData['image'][0]['type'] = $mime;
        } else {
            $leadershipData['image'] = null;
        }
        return $leadershipData;
    }

    /**
     * @return File
     */
    private function getFileInfo()
    {
        return $this->fileInfo;
    }
}