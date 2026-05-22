<?php

namespace Rptech\Feedback\Controller\Adminhtml\Index;

use Rptech\Feedback\Model\Feedback;

/**
 * Class MassDelete
 * @package Rptech\Feedback\Controller\Adminhtml\Address
 */
class MassDelete extends MassAction
{
    /**
     * Mass action
     *
     * @param Feedback $data
     * @return $this
     */
    protected function massAction(Feedback $data)
    {
        $this->dataRepository->delete($data);
        return $this;
    }
}
