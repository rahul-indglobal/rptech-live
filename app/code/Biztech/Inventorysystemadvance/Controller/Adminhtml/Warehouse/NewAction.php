<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

use Magento\Backend\App\Action;

class NewAction extends \Magento\Backend\App\Action
{
   
    /**
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
