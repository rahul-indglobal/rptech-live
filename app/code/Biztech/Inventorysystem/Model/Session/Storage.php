<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\Session;

class Storage extends \Magento\Framework\Session\Storage
{
    protected $storeManager;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string                                     $namespace
     * @param array                                      $data
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $namespace = 'supplier',
        array $data = []
    ) {
        if ($configShare->isWebsiteScope()) {
            $namespace .= '_' . $storeManager->getWebsite()->getCode();
        }
        parent::__construct($namespace, $data);
    }
}
