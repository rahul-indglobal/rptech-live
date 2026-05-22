<?php


namespace Rptech\Media\Model\Media;

use Magento\Framework\App\Request\DataPersistorInterface;
use Rptech\Media\Model\ResourceModel\Media\CollectionFactory;
use Rptech\Media\Model\MediaFactory;
use Rptech\Media\Model\Media;

/**
 * Class DataProvider
 * @package Rptech\Media\Model\Media
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var MediaFactory
     */
    private $mediaFactory;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $mediaCollectionFactory
     * @param MediaFactory $mediaFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $mediaCollectionFactory,
        MediaFactory $mediaFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $mediaCollectionFactory->create();
        $this->mediaFactory = $mediaFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var Media $media */
        foreach ($items as $media) {
            $mediaData = $media->getData();
            $this->loadedData[$media->getId()] = $mediaData;
        }

        $data = $this->dataPersistor->get('media');
        if (!empty($data)) {
            $media = $this->collection->getNewEmptyItem();
            $media->setData($data);
            $this->loadedData[$media->getId()] = $media->getData();
            $this->dataPersistor->clear('media');
        }
        return $this->loadedData;
    }
}