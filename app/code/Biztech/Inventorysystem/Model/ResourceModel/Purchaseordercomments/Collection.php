<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model\ResourceModel\Purchaseordercomments;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Biztech\Inventorysystem\Model\Purchaseordercomments', 'Biztech\Inventorysystem\Model\ResourceModel\Purchaseordercomments');
    }
}
