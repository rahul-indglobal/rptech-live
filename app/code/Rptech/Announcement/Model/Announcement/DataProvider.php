<?php

namespace Rptech\Announcement\Model\Announcement;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\Announcement\Model\Announcement;
use Rptech\Announcement\Model\AnnouncementFactory;
use Rptech\Announcement\Model\ResourceModel\Announcement\CollectionFactory;

/**
 * Class DataProvider
 * @package Rptech\Announcement\Model\Announcement
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var File
     */
    protected $fileInfo;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var AnnouncementFactory
     */
    protected $announcementFactory;
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
     * @param File $fileInfo
     * @param RequestInterface $request
     * @param AnnouncementFactory $announcementFactory
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Rptech\Announcement\Model\Announcement\File $fileInfo,
        RequestInterface $request,
        AnnouncementFactory $announcementFactory,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->fileInfo = $fileInfo;
        $this->request = $request;
        $this->announcementFactory = $announcementFactory;
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
        /** @var Announcement $announcement */
        foreach ($items as $announcement) {
            $announcementData = $announcement->getData();
            $announcementData = $this->convertValues($announcement, $announcementData);
            $this->loadedData[$announcement->getId()] = $announcementData;
        }

        $data = $this->dataPersistor->get('announcement');
        if (!empty($data)) {
            $announcement = $this->collection->getNewEmptyItem();
            $announcement->setData($data);
            $this->loadedData[$announcement->getId()] = $announcement->getData();
            $this->dataPersistor->clear('announcement');
        }
        return $this->loadedData;
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return [];
    }

    /**
     * @param $announcement
     * @param $announcementData
     * @return mixed
     */
    private function convertValues($announcement, $announcementData)
    {
        /**
         * @var \Rptech\Announcement\Model\Announcement $announcement
         */
        $fileName = $announcement->getData('pdf');
        $fileInfo = $this->getFileInfo();
        if ($fileName && $fileInfo->isFile($fileName)) {
            $stat = $fileInfo->getStat($fileName);
            $mime = $fileInfo->getMimeType($fileName);
            unset($announcementData['pdf']);
            $announcementData['pdf'][0]['name'] = basename($fileName);
            $announcementData['pdf'][0]['url'] = $announcement->getImageUrl();
            $announcementData['pdf'][0]['size'] = isset($stat) ? $stat['size'] : 0;
            $announcementData['pdf'][0]['type'] = $mime;
        } else {
            $announcementData['pdf'] = null;
        }
        return $announcementData;
    }

    /**
     * @return File
     */
    private function getFileInfo()
    {
        return $this->fileInfo;
    }
}