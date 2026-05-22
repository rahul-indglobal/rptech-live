<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Purchaseinvoice\Edit\Tab;

class View extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_systemStore;
    protected $_purchaseinvoiceModel;
    protected $_purchaseinvoiceitemsModel;
    protected $_currentStore;
    protected $_currencyInterface;
    protected $_supplierModel;
    protected $_invoiceStatus;
    
    /**
     * @param \Magento\Backend\Block\Template\Context               $context
     * @param \Magento\Framework\Registry                           $registry
     * @param \Magento\Framework\Data\FormFactory                   $formFactory
     * @param \Magento\Store\Model\System\Store                     $systemStore
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoice        $purchaseinvoiceModel
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoiceitems   $purchaseinvoiceitemsModel
     * @param \Magento\Store\Model\Store                            $currentStore
     * @param \Magento\Framework\Locale\CurrencyInterface           $currencyInterface
     * @param \Biztech\Inventorysystem\Model\Managesupplier         $supplierModel
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoice\Status $invoiceStatus
     * @param array                                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Biztech\Inventorysystem\Model\Purchaseinvoice $purchaseinvoiceModel,
        \Biztech\Inventorysystem\Model\Purchaseinvoiceitems $purchaseinvoiceitemsModel,
        \Magento\Store\Model\Store $currentStore,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel,
        \Biztech\Inventorysystem\Model\Purchaseinvoice\Status $invoiceStatus,
        array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        $this->_purchaseinvoiceModel = $purchaseinvoiceModel;
        $this->_purchaseinvoiceitemsModel = $purchaseinvoiceitemsModel;
        $this->_currentStore = $currentStore;
        $this->_currencyInterface = $currencyInterface;
        $this->_supplierModel = $supplierModel;
        $this->_invoiceStatus = $invoiceStatus;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('inventorysystem_purchaseinvoice');
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setUseContainer(true);
        $this->setTemplate('Biztech_Inventorysystem::purchaseinvoice/viewinvoice.phtml');
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Purchaseinvoice');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Purchaseinvoice');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Purchase invoice collection
     * @return Object
     */
    public function purchaseinvoiceCollection()
    {

        $invoiceID = $this->getRequest()->getParam('id');
        $piCollection = $this->_purchaseinvoiceModel->load($invoiceID);
        return $piCollection;
    }

    /**
     * Purchase invoice item collection
     * @return Object
     */
    public function purchaseinvoiceitemsCollection()
    {

        $invoiceID = $this->getRequest()->getParam('id');
        $piiCollection = $this->_purchaseinvoiceitemsModel->getCollection()->addFieldToFilter('invoice_id', $invoiceID);
        return $piiCollection;
    }

    /**
     * Current currency store
     * @return Object
     */
    public function getCurrentCurrencyStore()
    {
        return $this->_currentStore->getCurrentCurrencyCode();
    }

    /**
     * Local currency
     * @return Object
     */
    public function getLocalCurrency()
    {
        return $this->_currencyInterface;
    }

    /**
     * Supplier data
     * @return Object
     */
    public function getSupplier()
    {
        return $this->_supplierModel;
    }

    /**
     * Supplier invoice status
     * @return Array
     */
    public function getInvoiceStatus()
    {
        return $this->_invoiceStatus->toOptionArray();
    }
}
