<?php
    namespace Rptech\FileUpload\Model\ResourceModel\FileUpload;
    
    class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    {
        protected $_idFieldName = 'entity_id';
        protected $_eventPrefix = 'rptech_fileupload_collection';
        protected $_eventObject = 'fileupload_collection';
        
        /**
         * Define resource model
         *
         * @return void
         */
        protected function _construct()
        {
            $this->_init('Rptech\FileUpload\Model\FileUpload', 'Rptech\FileUpload\Model\ResourceModel\FileUpload');
        }
        
    }