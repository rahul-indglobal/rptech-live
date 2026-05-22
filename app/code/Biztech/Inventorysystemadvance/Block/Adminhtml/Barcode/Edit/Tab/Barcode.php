<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Barcode\Edit\Tab;

class Barcode extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_systemStore;
    protected $_barcodeModel;
    protected $_productCollection;
    protected $_stockItemRepository;
    protected $_resourceConnection;
    protected $_supplierCollection;
    protected $_countryModel;
    protected $_regionModel;
    protected $_purchaseordersModel;
    protected $_currentStore;
    protected $_bizHelper;
    protected $_advanceHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context                                $context
     * @param \Magento\Framework\Registry                                            $registry
     * @param \Magento\Framework\Data\FormFactory                                    $formFactory
     * @param \Magento\Store\Model\System\Store                                      $systemStore
     * @param \Biztech\Inventorysystemadvance\Model\Barcode                          $barcodeModel
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection                $productCollection
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository              $stockItemRepository
     * @param \Magento\Framework\App\ResourceConnection                              $resourceConnection
     * @param \Biztech\Inventorysystem\Model\ResourceModel\Managesupplier\Collection $supplierCollection
     * @param \Magento\Directory\Model\Country                                       $countryModel
     * @param \Magento\Directory\Model\Region                                        $regionModel
     * @param \Biztech\Inventorysystem\Model\Purchaseorders                          $purchaseordersModel
     * @param \Biztech\Inventorysystem\Helper\Data                                   $bizHelper
     * @param \Biztech\Inventorysystemadvance\Helper\Data                            $advanceHelper
     * @param array                                                                  $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Biztech\Inventorysystemadvance\Model\Barcode $barcodeModel,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockItemRepository,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Biztech\Inventorysystem\Model\ResourceModel\Managesupplier\Collection $supplierCollection,
        \Magento\Directory\Model\Country $countryModel,
        \Magento\Directory\Model\Region $regionModel,
        \Biztech\Inventorysystem\Model\Purchaseorders $purchaseordersModel,
        \Biztech\Inventorysystem\Helper\Data $bizHelper,
        \Biztech\Inventorysystemadvance\Helper\Data $advanceHelper,
        array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        $this->_barcodeModel = $barcodeModel;
        $this->_productCollection = $productCollection;
        $this->_stockItemRepository = $stockItemRepository;
        $this->_resourceConnection = $resourceConnection;
        $this->_supplierCollection = $supplierCollection;
        $this->_countryModel = $countryModel;
        $this->_regionModel = $regionModel;
        $this->_purchaseordersModel = $purchaseordersModel;
        $this->_currentStore = $context->getStoreManager();
        $this->_bizHelper = $bizHelper;
        $this->_advanceHelper = $advanceHelper;
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
        $model = $this->_coreRegistry->registry('barcode_data');

        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
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
        return __('Barcode Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Barcode Information');
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
     * Barcode details
     * @param  [int] $id
     * @return object
     */
    public function getBarcode($id)
    {
        return $this->_barcodeModel->load($id)->toArray();
    }

    /**
     * Product details
     * @param  [int] $id
     * @return object
     */
    public function getProduct($id)
    {
        $collection = $this->_productCollection->addAttributeToFilter('entity_id', array('eq' => $id))->addAttributeToSelect(array('name', 'small_image', 'price', 'cost', 'status', 'reck_no'))->getFirstItem()->getData();
        return $collection;
    }

    /**
     * Product stock details
     * @param  [int] $id
     * @return object
     */
    public function getStockRepo($productId)
    {

        return $this->_stockItemRepository->getStockItem($productId)->getData();
    }

    /**
     * Resource connection
     * @return object
     */
    public function getResource()
    {
        return $this->_resourceConnection;
    }

    /**
     * Supplier detilas
     * @param  [int] $supplier
     * @return object
     */
    public function getSupplier($supplier)
    {
        return $this->_supplierCollection->addFieldToFilter('main_table.supplier_id', $supplier)->join(array('sa' => $this->_resourceConnection->getTableName('bc_supplier_address_is')), 'main_table.supplier_id=sa.supplier_id', array('address_line', 'city', 'country', 'state', 'state_id', 'postal_code', 'telephone', 'fax'))->getFirstItem()->getData();
    }

    /**
     * country details
     * @param  [int] $country
     * @return object
     */
    public function getCountry($country)
    {
        return $this->_countryModel->loadByCode($country)->getName();
    }

    /**
     * Region details
     * @param  [int] $stateId
     * @param  [int] $country
     * @return object
     */
    public function getRegion($stateId, $country)
    {
        return $this->_regionModel->loadByCode($stateId, $country);
    }

    /**
     * PO details
     * @param  [int] $poId
     * @return object
     */
    public function getPurchaseOrder($poId)
    {
        return $this->_purchaseordersModel->load($poId);
    }

    /**
     * Current store details
     * @return object
     */
    public function getCurrentStore()
    {
        return $this->_currentStore->getStore();
    }

    /**
     * Biztech helper access
     * @return object
     */
    public function getBiztechHelper()
    {
        return $this->_bizHelper->getCurrencyCode();
    }

    /**
     * Biztech advance helper access
     * @param  [int] $id
     * @return object
     */
    public function getAdvanceHelper($id)
    {
        return $this->_advanceHelper->getWarehouseDetails($id);
    }
    public function getBarcodeImage($barcode)
    {
        return $this->_advanceHelper->barocdeImage($barcode);
    }
}
