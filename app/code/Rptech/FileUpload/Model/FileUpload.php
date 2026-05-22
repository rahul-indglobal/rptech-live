<?php
    namespace Rptech\FileUpload\Model;
    
    class FileUpload extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
    {
    
        const CACHE_TAG = 'rptech_fileupload';
    
        protected $_cacheTag = 'rptech_fileupload';
    
        protected $_eventPrefix = 'rptech_fileupload';
    
        protected function _construct()
        {
            $this->_init('Rptech\FileUpload\Model\ResourceModel\FileUpload');
        }
    
        /**
         * @return string[]
         */
        public function getIdentities()
        {
            return [self::CACHE_TAG . '_' . $this->getId()];
        }
    
        public function getDefaultValues()
        {
            $values = [];
        
            return $values;
        }
    }