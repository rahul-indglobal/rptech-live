<?php
/**
 * @author Rptech
 * @package Rptech_HomepageSlider
 */
namespace Rptech\HomepageSlider\Controller\Adminhtml\Slider;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Rptech\HomepageSlider\Model\HomepageSliderFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Rptech\HomepageSlider\Controller\Adminhtml\Slider
 */
class Edit extends Action
{
    protected $homepageSliderFactory;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        HomepageSliderFactory $homepageSliderFactory,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->homepageSliderFactory = $homepageSliderFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {

        $entityId = $this->getRequest()->getParam('entity_id');
        $model = $this->homepageSliderFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Slider is no longer exists.'));
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
        $this->coreRegistry->register('homepageslider',$model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_HomepageSlider::Slider')
            ->addBreadcrumb(__('HomepageSlider'), __('Edit'))
            ->addBreadcrumb(__('Manage HomepageSlider'), __('Manage HomepageSlider'));
        $resultPage->getConfig()->getTitle()->prepend(__('HomepageSlider'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add HomepageSlider'));
        return $resultPage;
    }
}