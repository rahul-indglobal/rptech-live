<?php

namespace Rptech\Event\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $registry;

    public function __construct(
        PageFactory $resultPageFactory,
        Registry $registry,
        Context $context
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        parent::__construct($context);
    }

    public function execute()
    {
        $post = $this->getRequest()->getParams();
        if (!empty($post) && isset($post['year'])) {
            $this->registry->register('year', $post['year']);
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Events'));
        return $resultPage;
    }
}