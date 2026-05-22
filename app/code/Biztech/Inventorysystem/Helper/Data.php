<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Helper;

use Biztech\Inventorysystem\Model\PurchaseordersFactory;
use Biztech\Inventorysystem\Model\PurchaseordersitemsFactory;
use Biztech\Inventorysystem\Model\StockreceivedFactory;
use Biztech\Inventorysystem\Model\StockreceiveditemsFactory;
use Biztech\Inventorysystemadvance\Model\Warehouse;
use Magento\Backend\Model\Auth\Session as BackendSession;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManager;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\Store;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Biztech\Inventorysystem\Model\PurchaseinvoiceFactory;
use Biztech\Inventorysystem\Model\PurchaseinvoiceitemsFactory;

class Data extends AbstractHelper
{

    const XML_PATH_DATA = 'inventorysystem/activation/data';
    const XML_PATH_INSTALLED = 'inventorysystem/activation/installed';
    const XML_PATH_WEBSITES = 'inventorysystem/activation/websites';

    protected $JsonFactory;
    protected $storeManager;
    protected $localeCurrency;
    protected $encryptor;
    protected $scopeConfig;
    protected $moduleDir;
    protected $_backendUrl;
    protected $_resource;
    protected $_warehouseModel;
    protected $_poModelFactory;
    protected $_poiModelFactory;
    protected $_srModelFactory;
    protected $_sriModelFactory;
    protected $_PurchaseinvoiceFactory;
    protected $_PurchaseinvoiceitemsFactory;
    protected $moduleManager;
    protected $authSession;
    private $eventManager;
    private $messageManager;
    private $_transportBuilder;
    protected $inlineTranslation;
    protected $customerUrl;
    protected $_purchaseorderModel;
    protected $_supplierModel;
    protected $_stockItem;
    protected $_stockState;
    protected $_stockItemQty;
    protected $_state;
    protected $_currentStore;
    protected $_currencyInterface;
    protected $_urlInterface;
    protected $_dateTime;
    protected $_warehouseproductFactory;
    /**
     * @param Context $context
     * @param JsonFactory $JsonFactory
     * @param StoreManager $storeManager
     * @param CurrencyInterface $localeCurrency
     * @param EncryptorInterface $encryptor
     * @param \Magento\Framework\Module\Dir\Reader $moduleDir
     * @param UrlInterface $backendUrl
     * @param ResourceConnection $resource
     * @param PurchaseordersFactory $poModelFactory
     * @param PurchaseordersitemsFactory $poiModelFactory
     * @param StockreceivedFactory $srModelFactory
     * @param StockreceiveditemsFactory $sriModelFactory
     * @param BackendSession $authSession
     * @param EventManager $eventManager
     * @param ManagerInterface $messageManager
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param TimezoneInterface $localeDate
     * @param PurchaseinvoiceFactory $PurchaseinvoiceFactory
     * @param PurchaseinvoiceitemsFactory $PurchaseinvoiceitemsFactory
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param \Biztech\Inventorysystem\Model\Purchaseorders $purchaseorderModel
     * @param \Biztech\Inventorysystem\Model\Managesupplier $supplierModel
     * @param \Magento\CatalogInventory\Model\StockRegistry $stockItem
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemQty
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Store\Model\Store $currentStore
     * @param \Magento\Framework\Locale\CurrencyInterface $currencyInterface
     * @param \Magento\Backend\Model\UrlInterface $urlInterface
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param Warehouse $warehouseModel
     * @param \Biztech\Inventorysystemadvance\Model\WarehouseproductFactory $warehouseproductFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $JsonFactory,
        StoreManager $storeManager,
        CurrencyInterface $localeCurrency,
        EncryptorInterface $encryptor,
        \Magento\Framework\Module\Dir\Reader $moduleDir,
        UrlInterface $backendUrl,
        ResourceConnection $resource,
        PurchaseordersFactory $poModelFactory,
        PurchaseordersitemsFactory $poiModelFactory,
        StockreceivedFactory $srModelFactory,
        StockreceiveditemsFactory $sriModelFactory,
        BackendSession $authSession,
        EventManager $eventManager,
        ManagerInterface $messageManager,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        TimezoneInterface $localeDate,
        PurchaseinvoiceFactory $PurchaseinvoiceFactory,
        PurchaseinvoiceitemsFactory $PurchaseinvoiceitemsFactory,
        \Magento\Customer\Model\Url $customerUrl,
        \Biztech\Inventorysystem\Model\Purchaseorders $purchaseorderModel,
        \Biztech\Inventorysystem\Model\Managesupplier $supplierModel,
        \Magento\CatalogInventory\Model\StockRegistry $stockItem,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemQty,
        \Magento\Framework\App\State $state,
        \Magento\Store\Model\Store $currentStore,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        \Magento\Backend\Model\UrlInterface $urlInterface,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        Warehouse $warehouseModel,
        \Biztech\Inventorysystemadvance\Model\WarehouseproductFactory $warehouseproductFactory
    ) {
        $this->JsonFactory = $JsonFactory;
        $this->storeManager = $storeManager;
        $this->localeCurrency = $localeCurrency;
        $this->encryptor = $encryptor;
        $this->scopeConfig = $context->getScopeConfig();
        $this->moduleDir = $moduleDir;
        $this->_backendUrl = $backendUrl;
        $this->_resource = $resource;
        $this->_warehouseModel = $warehouseModel;
        $this->moduleManager = $context->getModuleManager();
        $this->_poModelFactory = $poModelFactory;
        $this->_poiModelFactory = $poiModelFactory;
        $this->_srModelFactory = $srModelFactory;
        $this->_sriModelFactory = $sriModelFactory;
        $this->authSession = $authSession;
        $this->eventManager = $eventManager;
        $this->messageManager = $messageManager;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->_localeDate = $localeDate;
        $this->_PurchaseinvoiceFactory = $PurchaseinvoiceFactory;
        $this->_PurchaseinvoiceitemsFactory = $PurchaseinvoiceitemsFactory;
        $this->_customerUrl = $customerUrl;
        $this->_purchaseorderModel = $purchaseorderModel;
        $this->_supplierModel = $supplierModel;
        $this->_stockItem = $stockItem;
        $this->_stockState = $stockState;
        $this->_stockItemQty = $stockItemQty;
        $this->_state = $state;
        $this->_currentStore = $currentStore;
        $this->_currencyInterface = $currencyInterface;
        $this->_urlInterface = $urlInterface;
        $this->_dateTime = $dateTime;
        $this->_warehouseproductFactory = $warehouseproductFactory;
        parent::__construct($context);
    }

    /**
     * PO details
     * @return Object
     */
    public function getPOModel()
    {
        return $this->_poModelFactory->create();
    }

    /**
     * @return mixed
     */
    public function getDataInfo()
    {
        $data = $this->scopeConfig->getValue(self::XML_PATH_DATA, ScopeInterface::SCOPE_STORE);
        return json_decode(base64_decode($this->encryptor->decrypt($data)));
    }

    /**
     * @param $url
     * @return mixed
     */
    public function getFormatUrl($url)
    {
        $input = trim($url, '/');
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }
        $urlParts = parse_url($input);
        if (isset($urlParts['path'])) {
            $domain = preg_replace('/^www\./', '', $urlParts['host'] . $urlParts['path']);
        } else {
            $domain = preg_replace('/^www\./', '', $urlParts['host']);
        }
        return $domain;
    }

    /**
     * @return array
     */
    public function getAllStoreDomains()
    {
        $domains = array();
        foreach ($this->storeManager->getWebsites() as $website) {
            $url = $website->getConfig('web/unsecure/base_url');
            if ($domain = trim(preg_replace('/^.*?\/\/(.*)?\//', '$1', $url))) {
                $domains[] = $domain;
            }
            $url = $website->getConfig('web/secure/base_url');
            if ($domain = trim(preg_replace('/^.*?\/\/(.*)?\//', '$1', $url))) {
                $domains[] = $domain;
            }
        }
        return array_unique($domains);
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        $websiteId = $this->storeManager->getWebsite()->getId();
        $isenabled = $this->scopeConfig->getValue('inventorysystem/enableextension/enabled', ScopeInterface::SCOPE_STORE);
        if ($isenabled) {
            if ($websiteId) {
                $websites = $this->getAllWebsites();
                $key = $this->scopeConfig->getValue('inventorysystem/activation/key', ScopeInterface::SCOPE_STORE);
                if ($key == null || $key == '') {
                    return false;
                } else {
                    $en = $this->scopeConfig->getValue('inventorysystem/activation/en', ScopeInterface::SCOPE_STORE);
                    if ($isenabled && $en && in_array($websiteId, $websites)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                $en = $this->scopeConfig->getValue('inventorysystem/activation/en', ScopeInterface::SCOPE_STORE);
                if ($isenabled && $en) {
                    return true;
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getAllWebsites()
    {
        $value = $this->scopeConfig->getValue(self::XML_PATH_INSTALLED, ScopeInterface::SCOPE_STORE);
        if (!$value) {
            return array();
        }
        $data = $this->scopeConfig->getValue(self::XML_PATH_DATA, ScopeInterface::SCOPE_STORE);
        $web = $this->scopeConfig->getValue(self::XML_PATH_WEBSITES, ScopeInterface::SCOPE_STORE);
        $websites = explode(',', str_replace($data, '', $this->encryptor->decrypt($web)));
        $websites = array_diff($websites, array(""));
        return $websites;
    }

    /**
     * Products grid URL
     * @return String
     */
    public function getProductsGridUrl()
    {
        return $this->_backendUrl->getUrl('*/*/products', ['_current' => true]);
    }

    /**
     * @param $supplierData
     * @param string $preferredSupplier
     * @return string
     */
    public function getSupplierName($supplierData, $preferredSupplier = '', $itemId = null, $productId = null)
    {
        if (!empty($supplierData) && is_array($supplierData)) {
            if ($itemId == '') {
                $selectOptionHtml = "<select id='supplier' name='supplier[]' width='100%' class='required-entry admin__control-select selectSup'>";
            } else {
                $selectOptionHtml = "<select id='supplier_" . $itemId . "' name='supplier[]' width='100%' class='required-entry admin__control-select selectSup'>";
            }

            if (!is_null($productId)) {
                $selectOptionHtml = "<select id='supplier_" . $productId . "' name='supplier[" . $productId . "]' width='100%' class='required-entry admin__control-select selectSup'>";
            }

            $othrOptGrpOption = '';
            if ($preferredSupplier == '') {
                $selectOptionHtml .= "<optgroup label='Preferred Supplier'><option value=''>No supplier selected</option></optgroup>";
                for ($i = 0; $i < count($supplierData); $i++) {
                    $othrOptGrpOption .= '<option value="' . $supplierData[$i]['supplier_id'] . '">' . $supplierData[$i]['first_name'] . ' ' . $supplierData[$i]['last_name'] . '</option>';
                }
                $selectOptionHtml .= "<optgroup label='Other Supplier'>" . $othrOptGrpOption . "</optgroup>";
            } else {
                $prefSupArray = explode(",", $preferredSupplier);
                $prefOptGrpOption = '';
                $othrOptGrpOption = '';
                for ($i = 0; $i < count($supplierData); $i++) {
                    if (is_numeric(array_search($supplierData[$i]['supplier_id'], $prefSupArray))) {
                        $prefOptGrpOption .= '<option value="' . $supplierData[$i]['supplier_id'] . '">' . $supplierData[$i]['first_name'] . ' ' . $supplierData[$i]['last_name'] . '</option>';
                    } else {
                        $othrOptGrpOption .= '<option value="' . $supplierData[$i]['supplier_id'] . '">' . $supplierData[$i]['first_name'] . ' ' . $supplierData[$i]['last_name'] . '</option>';
                    }
                }
                $selectOptionHtml .= "<optgroup label='Preferred Supplier'>" . $prefOptGrpOption . "</optgroup>";
                if ($othrOptGrpOption != '') {
                    $selectOptionHtml .= "<optgroup label='Other Suppliers'>" . $othrOptGrpOption . "</optgroup>";
                }
            }

            return $selectOptionHtml .= "</select>";
        }
    }

    /**
     * @return \Magento\Framework\App\RequestInterface
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return bool
     */
    public function isRequestAdmin()
    {
        return strpos($this->_request->getPathInfo(), 'admin') === false;
    }

    /**
     * @return ResourceConnection
     */
    public function getResource()
    {
        return $this->_resource;
    }

    /**
     * @param $productId
     * @return string
     */
    public function getWareHouseName($productId, $itemId = '')
    {
        $html = '';
        if ($this->isModuleEnabled('Biztech_Inventorysystemadvance')) {
            $defaultWarehouseID = $this->getConfig('inventorysystem/inventorysystemadvance/default_warehouse_select');
            $connection = $this->_resource->getConnection();
            $tableName = $this->_resource->getTableName('bc_warehouse_product_is');
            $getIncrIds = $connection->select()
                    ->from($tableName, ['warehouse_id'])
                    ->where('product_id = ' . $productId);
            $getData = $connection->fetchAll($getIncrIds);
            if ($itemId == '') {
                $html = "<select class='admin__control-select required-entry warehouse_select' id='warehouse_{$productId}' name='warehouse[]'>";
            } else {
                $html = "<select class='admin__control-select required-entry warehouse_select' id='warehouse_{$itemId}' name='warehouse[]'>";
            }
            if (!empty($getData[0])) {
                for ($j = 0; $j < count($getData); $j++) {
                    $html .= '<option value="' . $getData[$j]['warehouse_id'] . '">' . $this->_warehouseModel->load($getData[$j]['warehouse_id'])->getWarehouseName() . "</option>";
                }
            }
        }
        return $html;
    }

    /**
     * @param $modulename
     * @return bool
     */
    public function isModuleEnabled($modulename)
    {
        return $this->moduleManager->isEnabled($modulename);
    }

    /**
     * @param $configPath
     * @return mixed
     */
    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * [sendEmail function used for the send email]
     * @param  $poId
     * @return void
     */
    public function sendEmail($poId)
    {
        $this->inlineTranslation->suspend();
        try {
            $storeId = $this->storeManager->getStore()->getStoreId();
            $poModel = $this->_purchaseorderModel->load($poId);
            $poItemsCollection = $poModel->getProducts($poModel);
            $supplierEmail = $this->_supplierModel->load($poModel->getSupplierId())->getEmail();
            $currentAdminUser = $this->getCurrentUser();
            $adminEmail = $currentAdminUser->getEmail();

            $senderEmail = $this->getConfig('trans_email/ident_general/email');
            $senderName = $this->getConfig('trans_email/ident_general/name');

            $template = 'purchaseorder_email_template';
            $emailSubject = __('Purchase Order:') . ' ' . $poModel->getPurchaseOrderId();
            $supplierEmails = explode("@", $supplierEmail);
            $supplierUser = $supplierEmails[0];
            $senderInfo = [
                'name' => $senderName,
                'email' => $senderEmail
            ];

            $recipient = [
                'name' => $supplierUser,
                'email' => $supplierEmail
            ];

            $transport = $this->_transportBuilder->setTemplateIdentifier(
                $template
            )->setTemplateOptions(
                [
                                'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                                'store' => Store::DEFAULT_STORE_ID
                            ]
            )->setTemplateVars(
                [
                                'store' => $this->storeManager->getStore(),
                                'date_created' => $this->_localeDate->formatDateTime(
                                    new \DateTime(),
                                    \IntlDateFormatter::MEDIUM,
                                    \IntlDateFormatter::MEDIUM
                                ),
                                'pomodel' => $poModel,
                                'poitemsmodel' => $poItemsCollection,
                                'subject' => $emailSubject
                            ]
            )->setFrom(
                ['email' => $senderEmail, 'name' => $senderName]
            )->addTo(
                $recipient['email'],
                $recipient['name']
            )
                    ->getTransport();
            $transport->sendMessage();
        } catch (\Exception $e) {
            $var1["result"] = "error";
            $message = $e->getMessage();
            if ($message == "") {
                $var1["message"] = __("Unable to submit your request. Please, try again later");
            } else {
                $var1["message"] = $message;
            }
            $data = json_encode($var1);
        }

        $this->inlineTranslation->suspend();
        return $this;
    }

    /**
     * @param $data
     * @return string
     */
    public function saveStockReceived($data)
    {

        $srArray = [];

        for ($i = 0; $i < count($data['supplier']); $i++) {
            $srArray[$data['supplier'][$i]][$data['product_id'][$i]] = [
                'name' => $data['name'][$i],
                'sku' => $data['sku'][$i],
                'qty' => $data['qty'][$i],
                'supplier' => $data['supplier'][$i],
                'cost' => $data['cost'][$i],
                'warehouse' => $data['warehouse'][$i],
                'input_cost' => $data['input_cost'][$i],
                'qty_purchased' => $data['qty_purchased'][$i],
                'qty_received' => $data['qty_received'][$i],
                'row_total' => $data['row_total'][$i]
            ];
        }
        if (isset($srArray) && is_array($srArray) && !empty($srArray)) {
            if ($this->isModuleEnabled('Biztech_Inventorysystemadvance')) {
                $_warehouseModelFactory = $this->_warehouseproductFactory->create();
            }
            foreach ($srArray as $supID => $sriData) {
                $srModel = $this->_srModelFactory->create();
                $poModel = $this->_poModelFactory->create();
                $srPODetails = $srModel->getCollection()
                        ->addFieldToFilter('purchaseorder_id', $data['po_incr_id'])
                        ->setOrder('id', 'DESC')
                        ->getFirstItem();
                if ($srPODetails->getData()) {
                    $getSRIncrId = $srPODetails->getStockreceivedId();
                    $parts = explode("-", $getSRIncrId);
                    $lastPart = $parts[3];
                    $append = $lastPart + 1;
                    $stckrcvdIncrId = $data['po_incr_id'] . "-SR-" . $append;
                } else {
                    $stckrcvdIncrId = $data['po_incr_id'] . "-SR-1";
                }

                if ($this->isAdmin()) {
                    $currentUser = $this->getCurrentUser();
                    $userEmail = $currentUser->getEmail();
                } else {
                    /* TODO GET customer email */
                    $userEmail = 'developer1.test@gmail.com';
                }

                $srModel = $this->_srModelFactory->create();
                $srModel->setStockreceivedId($stckrcvdIncrId);
                $srModel->setPurchaseorderId($data['po_incr_id']);
                $srModel->setSupplierId($supID);
                $srModel->setStatus($data['po_status']);
                if ($data['ship_cost'] == '') {
                    $data['ship_cost'] = 0;
                }
                $srModel->setShippingCost($data['ship_cost']);
                $srModel->setSubTotal($data['sub_total']);
                $srModel->setTotal($data['grand_total']);
                $srModel->setReceivedBy($userEmail);
                $srModel->setComment($data['stock_received_comment']);
                $srModel->save();

                $lastInsrtId = $srModel->getId();

                if ($lastInsrtId != null) {
                    /* START: update po details */
                    $poModel = $this->_poModelFactory->create();
                    $getPOData = $poModel->load($data['po_id']);
                    $poData = $getPOData->getData();

                    if ($getPOData->getShipCost() == null) {
                        $shipCost = $data['ship_cost'];
                    } else {
                        $shipCost = $data['ship_cost'] + $getPOData->getShipCost();
                    }

                    if ($getPOData->getShipSubTotal() == null) {
                        $subTotal = $data['sub_total'];
                    } else {
                        $subTotal = $data['sub_total'] + $getPOData->getShipSubTotal();
                    }

                    if ($getPOData->getShipGrandTotal() == null) {
                        $grandTtl = $data['grand_total'];
                    } else {
                        $grandTtl = $data['grand_total'] + $getPOData->getShipGrandTotal();
                    }

                    $poModel->setId($data['po_id'])
                            ->setStatus($data['po_status'])
                            ->setShipCost($shipCost)
                            ->setShipSubTotal($subTotal)
                            ->setShipGrandTotal($grandTtl)
                            ->save();
                    /* end update of po details */

                    /* start update sr items data and po items data */
                    foreach ($sriData as $itemID => $itemData) {
                        $srQtyRecievied = $itemData['qty_received'];
                        $sriModel = $this->_sriModelFactory->create();
                        $poiModel = $this->_poiModelFactory->create();
                        $sriModel->setStockreceivedId($lastInsrtId);
                        $sriModel->setProductId($itemID);
                        if ($this->isModuleEnabled('Biztech_Inventorysystemadvance')) {
                            $sriModel->setWarehouseId($itemData['warehouse']);
                        }
                        $sriModel->setProductName($itemData['name']);
                        $sriModel->setProductSku($itemData['sku']);
                        $sriModel->setQtyAvail($itemData['qty']);
                        $sriModel->setQtyPurchased($itemData['qty_purchased']);
                        $sriModel->setQtyReceived($itemData['qty_received']);
                        if ($itemData['input_cost'] != '') {
                            $sriModel->setCost($itemData['input_cost']);
                        } else {
                            $sriModel->setCost($itemData['cost']);
                        }
                        $sriModel->setRowTotal($itemData['row_total']);

                        $sriModel->save();

                        /* update po item details */
                        $poiCollection = $poiModel->getCollection()
                                ->addFieldToFilter('product_id', $itemID)
                                ->addFieldToFilter('purchase_order_id', $data['po_id']);
                        $getData = $poiCollection->getData();
                        $getPoiId = $getData[0]['id'];
                        $getQtyRec = $getData[0]['qty_received'];
                        $getRecRowTotal = $getData[0]['rec_row_total'];

                        if ($getQtyRec == null || is_null($getQtyRec)) {
                            $qtyRec = $itemData['qty_received'];
                        } else {
                            $qtyRec = $itemData['qty_received'] + $getQtyRec;
                        }

                        if ($getRecRowTotal == null || is_null($getRecRowTotal)) {
                            $recRowTotal = $itemData['row_total'];
                        } else {
                            $recRowTotal = $itemData['row_total'] + $getRecRowTotal;
                        }

                        $poiModel->setId($getPoiId)
                                ->setQtyReceived($qtyRec)
                                ->setRecRowTotal($recRowTotal)
                                ->save();
                        $stockItem = $this->_stockItem->getStockItem($itemID);
                        $StockState = $this->_stockState;
                        $stockItemQty = $this->_stockItemQty;

                        $existingQty = $stockItemQty->get($itemID)->getQty();

                        $newRecieved = $srQtyRecievied + $existingQty;
                        if (isset($newRecieved) && $newRecieved != '') {
                            $stockItem->setData('qty', $newRecieved);
                        }
                        $stockItem->save();
                    }
                    /* Update inventory log table */
                    if (isset($data['mobile_call']) && $data['mobile_call'] == 1) {
                        /* custom event fire */
                        $this->eventManager->dispatch('update_product_stock_after_stock_received', $data);
                    }
                } else {
                    $this->messageManager->addError(__('Stock Received was not inserted successfully!'));
                    return "Not inserted successfully!";
                }
            }
            if (isset($data['mobile_call']) && $data['mobile_call'] == 1) {
                return "Stock Received Successfully!";
            }
        }
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {

        $app_state = $this->_state;
        if ($app_state->getAreaCode() === \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE) {
            return true;
        }

        return false;
    }

    /**
     * @return \Magento\User\Model\User|null
     */
    public function getCurrentUser()
    {
        return $this->authSession->getUser();
    }

    /**
     * [getCurrencyCode function used for the get the current currency code]
     * @return $currencySymbol
     */
    public function getCurrencyCode()
    {

        $currencyCode = $this->_currentStore->getCurrentCurrencyCode();
        $_localeCurrency = $this->_currencyInterface;
        $currencySymbol = $_localeCurrency->getCurrency($currencyCode)->getSymbol();
        return $currencySymbol;
    }

    /**
     * [savePurchaseInvoice used for the save the purchase invoice of the PO]
     * @param  $data
     * @return void
     */
    public function savePurchaseInvoice($data)
    {
        $piArray = array();
        for ($i = 0; $i < count($data['supplier']); $i++) {
            $piArray[$data['supplier'][$i]][$data['product_id'][$i]] = array('name' => $data['name'][$i], 'sku' => $data['sku'][$i], 'supplier' => $data['supplier'][$i], 'cost' => $data['input_cost'][$i], 'qty_purchased' => $data['req_qty'][$i], 'qty_received' => $data['rec_qty'][$i], 'row_total' => $data['row_total'][$i]);
        }

        if (isset($piArray) && is_array($piArray) && !empty($piArray)) {
            $backendUrl = $this->_urlInterface;
            foreach ($piArray as $supID => $piiData) {
                $piModel = $this->_PurchaseinvoiceFactory->create();
                $getLastIncrID = $prchsInvIncrId = "INV-" . $data['po_incr_id'];
                $getPreviousInv = $piModel->getCollection()->addFieldToFilter('invoice_incr_id', $prchsInvIncrId);
                $fetchData = $getPreviousInv->getData();
                if (is_array($fetchData) && !empty($fetchData)) {
                    $i = "Invoice already generated for this Purchase Order.";
                    $this->messageManager->addError($i);
                    $redirect = $backendUrl->getUrl('*/*/*');
                    //$this->responseFactory->create()->setRedirect($redirect)->sendResponse();
                    return false;
                }

                /* save stock receive data */
                $piModel->setInvoiceIncrId($prchsInvIncrId);
                $objDate = $this->_dateTime;
                $date = $objDate->gmtDate();
                if ($piModel->getCreatedAt() == null) {
                    $piModel->setCreatedAt($date);
                }
                $piModel->setUpdatedAt($date);
                $piModel->setPurchaseOrderId($data['po_incr_id']);
                $piModel->setPoId($data['po_id']);
                $piModel->setSupplierId($supID);
                $piModel->setShipCost($data['ship_cost']);
                $piModel->setTaxAmount($data['tax_amount']);
                $piModel->setDiscount($data['discount']);
                $piModel->setSubTotal($data['sub_total']);
                $piModel->setGrandTotal($data['grand_total']);
                $piModel->setComments($data['invoice']['comment_text']);
                $piModel->save();

                /* get last inserted id */
                $lastInsrtId = $piModel->getId();
                if ($lastInsrtId != null) {
                    $poModel = $this->_poModelFactory->create();
                    $poModel->setId($data['po_id'])
                            ->setInvoiced(1)
                            ->save();

                    foreach ($piiData as $itemID => $itemData) {
                        $purInvItmModel = $this->_PurchaseinvoiceitemsFactory->create();
                        $purInvItmModel->setInvoiceId($lastInsrtId);
                        $purInvItmModel->setProductId($itemID);
                        $purInvItmModel->setProductName($itemData['name']);
                        $purInvItmModel->setProductSku($itemData['sku']);
                        $purInvItmModel->setQtyPurchased($itemData['qty_purchased']);
                        $purInvItmModel->setQtyReceived($itemData['qty_received']);
                        $purInvItmModel->setCost($itemData['cost']);
                        $purInvItmModel->setRowTotal($itemData['row_total']);
                        $purInvItmModel->save();
                    }
                } else {
                    $msg = "Purchase Invoice not created!";
                    $this->messageManager->addError($msg);
                    $redirect = $backendUrl->getUrl('*/*/*');
                    //$this->responseFactory->create()->setRedirect($redirect)->sendResponse();
                    return false;
                }
            }
            return "Invoice Create Successfully.";
        }
    }
}
