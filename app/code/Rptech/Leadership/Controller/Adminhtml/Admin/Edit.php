<?php
namespace Rptech\Leadership\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Rptech\Leadership\Model\LeadershipFactory
     */
    protected $leadershipFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    public function __construct
    (
        \Magento\Backend\App\Action\Context $context,
        \Rptech\Leadership\Model\LeadershipFactory $leadershipFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    )
    {
        $this->leadershipFactory = $leadershipFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        $model = $this->leadershipFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Leader is no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        //Set entered data if was error when do save
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->coreRegistry->register('leadership', $model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_Leadership::leadership');
        $resultPage->getConfig()->getTitle()->prepend(__('Leadership Form'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Leader'));
        return $resultPage;
    }
}