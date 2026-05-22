<?php

namespace Rptech\Brand\Model\Brand;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\Brand\Model\Brand;
use Rptech\Brand\Model\BrandFactory;
use Rptech\Brand\Model\Brand\File;
use Rptech\Brand\Model\ResourceModel\Brand\CollectionFactory;

/**
 * Class DataProvider
 * @package Rptech\Brand\Model\Brand
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var \Rptech\Brand\Model\Brand\File
     */
    protected $fileInfo;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var BrandFactory
     */
    protected $brandFactory;
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
     * @param \Rptech\Brand\Model\Brand\File $fileInfo
     * @param RequestInterface $request
     * @param BrandFactory $brandFactory
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Rptech\Brand\Model\Brand\File $fileInfo,
        RequestInterface $request,
        BrandFactory $brandFactory,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->fileInfo = $fileInfo;
        $this->request = $request;
        $this->brandFactory = $brandFactory;
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
        /** @var Brand $brand */
        foreach ($items as $brand) {
            $brandData = $brand->getData();
            $brandData = $this->convertValues($brand, $brandData);
            $this->loadedData[$brand->getId()] = $brandData;
        }

        $data = $this->dataPersistor->get('brand');
        if (!empty($data)) {
            $brand = $this->collection->getNewEmptyItem();
            $brand->setData($data);
            $this->loadedData[$brand->getId()] = $brand->getData();
            $this->dataPersistor->clear('brand');
        }
        return $this->loadedData;
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return [];
    }

    /**
     * @param $brand
     * @param $brandData
     * @return mixed
     */
    private function convertValues($brand, $brandData)
    {
        /**
         * @var \Rptech\Brand\Model\Brand $brand
         */
        $fileName = $brand->getData('image');
        $fileInfo = $this->getFileInfo();
        if ($fileName && $fileInfo->isFile($fileName)) {
            $stat = $fileInfo->getStat($fileName);
            $mime = $fileInfo->getMimeType($fileName);
            unset($brandData['image']);
            $brandData['image'][0]['name'] = basename($fileName);
            $brandData['image'][0]['url'] = $brand->getImageUrl();
            $brandData['image'][0]['size'] = isset($stat) ? $stat['size'] : 0;
            $brandData['image'][0]['type'] = $mime;
        } else {
            $brandData['image'] = null;
        }
        return $brandData;
    }

    /**
     * @return File
     */
    private function getFileInfo()
    {
        return $this->fileInfo;
    }
}