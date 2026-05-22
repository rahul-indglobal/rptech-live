<?php

namespace Lalita\Mysmsmodule\Model;

use Magento\Framework\App\Request\DataPersistorInterface;
use Lalita\Mysmsmodule\Model\ResourceModel\Enquiry\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Lalita\Mysmsmodule\Model\EnquiryFactory;

/**
 * Class DataProvider
 * @package Lalita\Mysmsmodule\Model
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var Collection;
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;
    /**
     * @var
     */
    protected $enquryFactory;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    
    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $enquiryCollectionFactory
     * @param \Lalita\Mysmsmodule\Model\EnquiryFactory $enquryFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $enquiryCollectionFactory,
        EnquiryFactory $enquryFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $enquiryCollectionFactory->create();
        $this->addressFactory = $enquryFactory;
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
        return [];
    }
}