<?php

namespace Rptech\Leadership\Model\ResourceModel;

class RedundantLeadershipImageChecker
{
    /**
     * @var Leadership\CollectionFactory
     */
    private $collectionFactory;

    /**
     * RedundantBrandImageChecker constructor.
     * @param Leadership\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Rptech\Leadership\Model\ResourceModel\Leadership\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param string $imageName
     * @return bool
     */
    public function execute(string $imageName): bool
    {
        $brands = $this->collectionFactory->create()->addFieldToFilter('image', $imageName);
        return empty($brands->getSize());
    }
}
