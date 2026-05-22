<?php

namespace Rptech\HomepageSlider\Model\HomepageSlider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\HomepageSlider\Model\HomepageSlider;
use Rptech\HomepageSlider\Model\HomepageSliderFactory;
use Rptech\HomepageSlider\Model\ResourceModel\HomepageSlider\CollectionFactory;

/**
 * Class DataProvider
 * @package Rptech\HomepageSlider\Model\HomepageSlider
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
     * @var HomepageSliderFactory
     */
    protected $homepageSliderFactory;
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
     * @param HomepageSliderFactory $homepageSliderFactory
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Rptech\HomepageSlider\Model\HomepageSlider\File $fileInfo,
        RequestInterface $request,
        HomepageSliderFactory $homepageSliderFactory,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->fileInfo = $fileInfo;
        $this->request = $request;
        $this->homepageSliderFactory = $homepageSliderFactory;
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
        /** @var HomepageSlider $homepageslider */
        foreach ($items as $homepageslider) {
            $homepagesliderData = $homepageslider->getData();
            $homepagesliderData = $this->convertValues($homepageslider, $homepagesliderData);
            $this->loadedData[$homepageslider->getId()] = $homepagesliderData;
        }

        $data = $this->dataPersistor->get('homepageslider');
        if (!empty($data)) {
            $homepageslider = $this->collection->getNewEmptyItem();
            $homepageslider->setData($data);
            $this->loadedData[$homepageslider->getId()] = $homepageslider->getData();
            $this->dataPersistor->clear('homepageslider');
        }
        return $this->loadedData;
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return [];
    }

    /**
     * @param $homepageslider
     * @param $homepagesliderData
     * @return mixed
     */
    private function convertValues($homepageslider, $homepagesliderData)
    {
        /**
         * @var \Rptech\HomepageSlider\Model\HomepageSlider $homepageslider
         * @var \Rptech\HomepageSlider\Model\HomepageSlider\File $fileInfo
         */
        $fileName = $homepageslider->getData('image');

        $fileInfo = $this->getFileInfo();
        if ($fileName && $fileInfo->isFile($fileName)) {
            $stat = $fileInfo->getStat($fileName);
            $mime = $fileInfo->getMimeType($fileName);
            unset($homepagesliderData['image']);
            $homepagesliderData['image'][0]['name'] = basename($fileName);
            $homepagesliderData['image'][0]['url'] = $homepageslider->getImageUrl();
            $homepagesliderData['image'][0]['size'] = isset($stat) ? $stat['size'] : 0;
            $homepagesliderData['image'][0]['type'] = $mime;
        } else {
            $homepagesliderData['image'] = null;
        }
        return $homepagesliderData;
    }

    /**
     * @return File
     */
    private function getFileInfo()
    {
        return $this->fileInfo;
    }
}