<?php

namespace Rptech\Event\Controller\Details;

use Magento\Framework\App\Action\Context;
use Rptech\Event\Model\EventFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;


/**
 * Class Index
 * @package Rptech\Event\Controller\Details
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var EventFactory
     */
    protected $eventFactory;

    /**
     * Index constructor.
     *
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param EventFactory $eventFactory
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Registry $registry,
        EventFactory $eventFactory,
        Context $context
    )
    {
        $this->eventFactory = $eventFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Event Details'));
        if ($id){
            $model = $this->eventFactory->create();
            $event = $model->load($id);
            $this->coreRegistry->register('event', $event);
        }
        return $resultPage;
    }
}
