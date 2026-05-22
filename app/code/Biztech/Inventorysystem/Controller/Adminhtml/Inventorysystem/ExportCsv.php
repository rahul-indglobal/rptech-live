<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Controller\Adminhtml\Inventorysystem;

use Magento\Framework\Module\Manager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Biztech\Inventorysystemadvance\Helper\Data;

class ExportCsv extends \Magento\Backend\App\Action
{

    protected $moduleManager;
    protected $scopeConfig;
    protected $resultPageFactory;
    protected $inventorysystemHelper;
    public $catalogConfig;
    protected $productFactory;
    protected $request;
    protected $responseInterface;
    protected $resourceConnection;
    protected $_inventorysystemadvanceHelper;
    protected $warehouseModel;
    protected $urlInterface;

    /**
     * @param \Magento\Backend\App\Action\Context             $context
     * @param Manager                                         $moduleManager
     * @param ScopeConfigInterface                            $scopeConfig
     * @param \Magento\Framework\View\Result\PageFactory      $resultPageFactory
     * @param \Biztech\Inventorysystem\Helper\Data            $inventorysystemHelper
     * @param \Magento\Catalog\Model\ProductFactory           $productFactory
     * @param \Magento\Catalog\Model\Config                   $catalogConfig
     * @param \Magento\Framework\App\Request\Http             $request
     * @param \Magento\Framework\App\ResourceConnection       $resourceConnection
     * @param Data                                            $inventorysystemadvanceHelper
     * @param \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Manager $moduleManager,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        Data $inventorysystemadvanceHelper,
        \Biztech\Inventorysystemadvance\Model\Warehouse $warehouseModel
    ) {
        
        $this->scopeConfig = $scopeConfig;
        $this->resultPageFactory = $resultPageFactory;
        $this->inventorysystemHelper = $inventorysystemHelper;
        $this->moduleManager = $moduleManager;
        $this->productFactory = $productFactory;
        $this->catalogConfig = $catalogConfig;
        $this->request = $request;
        $this->responseInterface = $context->getResponse();
        $this->resourceConnection = $resourceConnection;
        $this->warehouseModel = $warehouseModel;
        $this->urlInterface = $context->getBackendUrl();
        parent::__construct($context);
    }

    /**
     * This function is used for export all products with warehouse qty details
     * @return Void
     */
    public function execute()
    {
        $websites = $this->inventorysystemHelper->getAllWebsites();
        
        $this->_resources = $this->resourceConnection;
        $connection = $this->_resources->getConnection();
        $collections = $this->productFactory->create()->getCollection();
        $collections->addAttributeToSelect($this->catalogConfig->getProductAttributes())
                ->addWebsiteFilter($websites);

        $collections->joinField('qty', $connection->getTableName('cataloginventory_stock_item'), 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        $collections->addAttributeToFilter(array(array('attribute' => 'type_id', 'nin' => array('grouped', 'configurable', 'bundle'))));

        $collections->setOrder('entity_id', 'DESC');
        $response = [
            ['sku', 'Total Qty', 'Warehouse Qty']
        ];
        foreach ($collections as $data) {
            $warehouseQty = $this->getWarehouseqty($data->getId());
            $response[] = [$data->getSku(), intVal($data->getQty()), $warehouseQty];
        }
        $content = '';
        $fileName = 'products_inventory.csv';
        foreach ($response as $line) {
            $content .= '"' . implode('","', $line) . '",' . "\n";
        }
        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     * This function is used for prepare the sheet of the products
     * @param  String $fileName
     * @param  String $content
     * @param  string $contentType
     * @return void
     */
    public function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
        
        $response = $this->responseInterface;
        $response->setHttpResponseCode(200);
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        return;
    }

    /**
     * This function is used for get the warehouse products qty
     * @param  int $rowId
     * @return mixed
     */
    public function getWarehouseqty($rowId)
    {

        $txtbox = '';
        $this->_resources = $this->resourceConnection;
        $connection = $this->_resources->getConnection();

        $tableName = $this->_resources->getTableName('bc_warehouse_product_is');

        $getIncrIds = $connection->select()
                ->from($tableName, array('warehouse_id', 'quantity'))
                ->where('product_id = ' . $rowId);

        $getData = $connection->fetchAll($getIncrIds);

        if (!empty($getData[0])) {
            $warehouseModel = $this->warehouseModel;
            $backendUrl = $this->urlInterface;

            if (!$this->request->getParam('inventorysystem') && !$this->request->getParam('demo_csv')) {
                for ($i = 0; $i < count($getData); $i++) {
                    $txtbox .= "<a target='_blank' href='" . $backendUrl->getUrl('inventorysystemadvance/warehouse/edit', array('id' => $getData[$i]['warehouse_id'])) . "'>" . $warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . "</a>: " . $getData[$i]['quantity'];
                    $txtbox .= "<br />";
                }
            } else {
                for ($i = 0; $i < count($getData); $i++) {
                    if ($i == count($getData) - 1) {
                        $txtbox .= $warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . ":" . $getData[$i]['quantity'];
                    } else {
                        $txtbox .= $warehouseModel->load($getData[$i]['warehouse_id'])->getWarehouseName() . ":" . $getData[$i]['quantity'] . ";";
                    }
                }
            }
        } else {
            $txtbox .= '';
        }
        return $txtbox;
    }
}
