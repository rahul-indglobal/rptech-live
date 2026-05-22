<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\Stockreceived;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;

abstract class AbstractSR extends \Magento\Backend\App\Action
{
    protected $escaper;
    protected $resultPageFactory;
    protected $resultForwardFactory;
    protected $_timezone;
    protected $_stockreceivedModel;

    /**
     * @param Action\Context                               $context              [description]
     * @param \Magento\Framework\Escaper                   $escaper              [description]
     * @param PageFactory                                  $resultPageFactory    [description]
     * @param Timezone                                     $timezone             [description]
     * @param ForwardFactory                               $resultForwardFactory [description]
     * @param \Biztech\Inventorysystem\Model\Stockreceived $stockreceivedModel   [description]
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Escaper $escaper,
        PageFactory $resultPageFactory,
        Timezone $timezone,
        ForwardFactory $resultForwardFactory,
        \Biztech\Inventorysystem\Model\Stockreceived $stockreceivedModel
    ) {
        parent::__construct($context);
        $this->escaper = $escaper;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_timezone = $timezone;
        $this->_stockreceivedModel = $stockreceivedModel;
    }

    /**
     * @return Object
     */
    public function getStockReceivedModel()
    {
        return $this->_stockreceivedModel;
    }
}
