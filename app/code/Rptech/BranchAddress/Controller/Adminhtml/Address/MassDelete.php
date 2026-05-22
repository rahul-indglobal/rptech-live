<?php

namespace Rptech\BranchAddress\Controller\Adminhtml\Address;

use Rptech\BranchAddress\Model\BranchAddress;

/**
 * Class MassDelete
 * @package Rptech\BranchAddress\Controller\Adminhtml\Address
 */
class MassDelete extends MassAction
{
    /**
     * Mass action
     *
     * @param BranchAddress $data
     * @return $this
     */
    protected function massAction(BranchAddress $data)
    {
        $this->dataRepository->delete($data);
        return $this;
    }
}
