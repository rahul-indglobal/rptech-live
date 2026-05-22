<?php

namespace Rptech\Media\Model\Source;

use Magento\Framework\Option\ArrayInterface;
use Rptech\Brand\Model\ResourceModel\Brand\CollectionFactory;

/**
 * Class Options
 * @package Rptech\Media\Model\Source
 */
class Options implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    private $brandCollection;

    public function __construct
    (
        CollectionFactory $collectionFactory
    )
    {
        $this->brandCollection = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [['value'=> '', 'label' => 'Please Select']];
        $brandCollection = $this->brandCollection->create();
        if(!empty($brandCollection)) {
            foreach ($brandCollection as $brand) {
                $options[] = [
                    'value' => $brand->getId(),
                    'label' => $brand->getTitle()
                ];
            }
        }
        return $options;
    }
}