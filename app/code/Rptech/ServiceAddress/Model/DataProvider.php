<?php
/**
 * @author Rptech
 * @package Rptech_ServiceAddress
 */
namespace Rptech\ServiceAddress\Model;

use Magento\Framework\App\Request\DataPersistorInterface;
use Rptech\ServiceAddress\Model\ResourceModel\Address\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\ServiceAddress\Model\AddressFactory;

/**
 * Class DataProvider
 * @package Rptech\ServiceAddress\Model
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
     * @var \Rptech\ServiceAddress\Model\AddressFactory
     */
    protected $addressFactory;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $addressCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $addressCollectionFactory,
        AddressFactory $addressFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $addressCollectionFactory->create();
        $this->addressFactory = $addressFactory;
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
        /** @var Address $address */
        foreach ($items as $address) {
            /** @var Address $address */
            $addressData = $address->getData();
            $this->loadedData[$address->getId()] = $addressData;
        }

        $data = $this->dataPersistor->get('address');
        if (!empty($data)) {
            $address = $this->collection->getNewEmptyItem();
            $address->setData($data);
            $this->loadedData[$address->getId()] = $address->getData();
            $this->dataPersistor->clear('address');
        }

        return $this->loadedData;
    }
}