<?php

namespace Rptech\Awards\Model\Awards;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\Awards\Model\Awards;
use Rptech\Awards\Model\AwardsFactory;
use Rptech\Awards\Model\ResourceModel\Awards\CollectionFactory;

/**
 * Class DataProvider
 * @package Rptech\Awards\Model\Awards
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
     * @var AwardsFactory
     */
    protected $awardsFactory;
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
     * @param AwardsFactory $awardsFactory
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Rptech\Awards\Model\Awards\File $fileInfo,
        RequestInterface $request,
        AwardsFactory $awardsFactory,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->fileInfo = $fileInfo;
        $this->request = $request;
        $this->awardsFactory = $awardsFactory;
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
        /** @var Awards $award */
        foreach ($items as $award) {
            $awardData = $award->getData();
            $awardData = $this->convertValues($award, $awardData);
            $this->loadedData[$award->getId()] = $awardData;
        }

        $data = $this->dataPersistor->get('award');
        if (!empty($data)) {
            $award = $this->collection->getNewEmptyItem();
            $award->setData($data);
            $this->loadedData[$award->getId()] = $award->getData();
            $this->dataPersistor->clear('award');
        }
        return $this->loadedData;
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return [];
    }

    /**
     * @param $award
     * @param $awardData
     * @return mixed
     */
    private function convertValues($award, $awardData)
    {
        /**
         * @var \Rptech\Awards\Model\Awards $award
         * @var \Rptech\Awards\Model\Awards\File $fileInfo
         */
        $fileName = $award->getData('image');

        $fileInfo = $this->getFileInfo();
        if ($fileName && $fileInfo->isFile($fileName)) {
            $stat = $fileInfo->getStat($fileName);
            $mime = $fileInfo->getMimeType($fileName);
            unset($awardData['image']);
            $awardData['image'][0]['name'] = basename($fileName);
            $awardData['image'][0]['url'] = $award->getImageUrl();
            $awardData['image'][0]['size'] = isset($stat) ? $stat['size'] : 0;
            $awardData['image'][0]['type'] = $mime;
        } else {
            $awardData['image'] = null;
        }
        return $awardData;
    }

    /**
     * @return File
     */
    private function getFileInfo()
    {
        return $this->fileInfo;
    }
}