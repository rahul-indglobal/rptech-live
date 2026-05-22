<?php

namespace Rptech\CareerOpportunities\Controller\Adminhtml\Index;

use Rptech\CareerOpportunities\Model\CareerOpportunities;

/**
 * Class MassDelete
 * @package Rptech\CareerOpportunities\Controller\Adminhtml\Address
 */
class MassDelete extends MassAction
{
    /**
     * Mass action
     *
     * @param CareerOpportunities $data
     * @return $this
     */
    protected function massAction(CareerOpportunities $data)
    {
        $this->dataRepository->delete($data);
        return $this;
    }
}
