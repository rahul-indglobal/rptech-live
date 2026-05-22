<?php
    namespace Rptech\FileUpload\Model;
    
    use Rptech\FileUpload\Model\ResourceModel\FileUpload\CollectionFactory;
    
    class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
    {
        /**
         * @param string $name
         * @param string $primaryFieldName
         * @param string $requestFieldName
         * @param CollectionFactory $fileCollectionFactory
         * @param array $meta
         * @param array $data
         */
        public function __construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            CollectionFactory $fileCollectionFactory,
            array $meta = [],
            array $data = []
        ) {
            $this->collection = $fileCollectionFactory->create();
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