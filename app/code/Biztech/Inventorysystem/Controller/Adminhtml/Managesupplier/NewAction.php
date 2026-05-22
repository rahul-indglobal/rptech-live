<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Managesupplier;

use Magento\Backend\App\Action;

class NewAction extends \Magento\Backend\App\Action
{
    /**
     * New Action
     * @return Void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
