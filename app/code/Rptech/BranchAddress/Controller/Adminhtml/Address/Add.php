<?php

namespace Rptech\BranchAddress\Controller\Adminhtml\Address;

use Magento\Backend\Model\View\Result\Forward;
use Rptech\BranchAddress\Controller\Adminhtml\AbstractBranchAddress;

/**
 * Class Add
 * @package Rptech\BranchAddress\Controller\Adminhtml\BranchAddress
 */
class Add extends AbstractBranchAddress
{
    /**
     * Forward to edit
     *
     * @return Forward
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
