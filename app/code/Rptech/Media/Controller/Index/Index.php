<?php

namespace Rptech\Media\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

/**
 * Class Index
 * @package Rptech\Media\Controller\Index
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

        if(isset($post['brand_id']) || isset($post['media_year'])){
            $this->registry->register('brand_id', $post['brand_id']);
            $this->registry->register('media_year', $post['media_year']);
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Media'));
        return $resultPage;
    }
}