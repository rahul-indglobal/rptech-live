<?php
/**
 * @author Rptech
 * @package Rptech_ServiceAddress
 */
namespace Rptech\ServiceAddress\Controller\Adminhtml\Address;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;
use Rptech\ServiceAddress\Model\AddressFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Rptech\ServiceAddress\Controller\Adminhtml\Address
 */
class Edit extends Action
{
    /**
     * @var AddressFactory
     */
    protected $addressFactory;
    /**
     * @var Registry
     */
    protected $coreRegistry;

    public function __construct(
        Context $context,
        AddressFactory $addressFactory,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->addressFactory = $addressFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        $model = $this->addressFactory->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This address is no longer exists.'));
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
        $this->coreRegistry->register('address',$model);
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rptech_ServiceAddress::address')
            ->addBreadcrumb(__('Service Address'), __('Address'))
            ->addBreadcrumb(__('Manage Addresses'), __('Manage Address'));
        $resultPage->getConfig()->getTitle()->prepend(__('Service Address'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getCityName() : __('New Address'));
        return $resultPage;        
    }
}