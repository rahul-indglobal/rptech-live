<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Barcode;

use Magento\Backend\App\Action;

class NewAction extends \Magento\Backend\App\Action
{
    /**
     * Initialization
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
