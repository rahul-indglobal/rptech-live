<?php

namespace Rptech\Leadership\Model\Leadership;

use Rptech\Leadership\Model\Leadership\File;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\Leadership\Model\Leadership;
use Rptech\Leadership\Model\LeadershipFactory;
use Rptech\Leadership\Model\ResourceModel\Leadership\Collection;
use Rptech\Leadership\Model\ResourceModel\Leadership\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var File
     */
    private $fileInfo;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var LeadershipFactory
     */
    private $leadershipFactory;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Rptech\Leadership\Model\Leadership\File $fileInfo
     * @param RequestInterface $request
     * @param LeadershipFactory $leadershipFactory
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct
    (
        $name,
        $primaryFieldName,
        $requestFieldName,
        File $fileInfo,
        RequestInterface $request,
        LeadershipFactory $leadershipFactory,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    )
    {
        $this->fileInfo = $fileInfo;
        $this->request = $request;
        $this->leadershipFactory = $leadershipFactory;
        $this->dataPersistor = $dataPersistor;
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var Leadership $leadership */
        foreach ($items as $leadership) {
            /** @var Leadership $leadership */
            $leadershipData = $leadership->getData();
            $leadershipData = $this->convertValues($leadership, $leadershipData);
            $this->loadedData[$leadership->getId()] = $leadershipData;
        }

        $data = $this->dataPersistor->get('leadership');
        if (!empty($data)) {
            $leadership = $this->collection->getNewEmptyItem();
            $leadership->setData($data);
            $this->loadedData[$leadership->getId()] = $leadership->getData();
            $this->dataPersistor->clear('leadership');
        }
        return $this->loadedData;
    }

    /**
     * @param $leadership
     * @param $leadershipData
     * @return mixed
     */
    private function convertValues($leadership, $leadershipData)
    {
        $fileName = $leadership->getData('image');
        $fileInfo = $this->getFileInfo();
        if ($fileName && $fileInfo->isFile($fileName)) {
            $stat = $fileInfo->getStat($fileName);
            $mime = $fileInfo->getMimeType($fileName);
            unset($leadershipData['image']);
            $leadershipData['image'][0]['name'] = basename($fileName);
            $leadershipData['image'][0]['url'] = $leadership->getImageUrl();
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
