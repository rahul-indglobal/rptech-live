<?php

namespace Biztech\Inventorysystem\Controller\Adminhtml\Inventorysystem;

use Magento\Framework\Module\Manager;
use Biztech\Inventorysystemadvance\Helper\Data;

class UpdateInventory extends \Magento\Backend\App\Action
{

    protected $_moduleManager;
    protected $_scopeConfig;
    protected $_stockitemRepository;
    protected $_productModel;
    protected $_eventManager;
    protected $inventorysystemadvanceHelper;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Manager $moduleManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockitemRepository,
        \Magento\Catalog\Model\Product $productModel,
        \Magento\Framework\Event\Manager $eventManager,
        Data $inventorysystemadvanceHelper
    ) {

        $this->_moduleManager = $moduleManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_stockitemRepository = $stockitemRepository;
        $this->_productModel = $productModel;
        $this->_eventManager = $eventManager;
        $this->inventorysystemadvanceHelper = $inventorysystemadvanceHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $data = $this->getRequest()->getParams();
            if (isset($data['updatestockdata'])) {
                $getParams = $data['updatestockdata'];
            } else {
                $getParams = '';
            }
            if ($this->_moduleManager->isEnabled('Biztech_Inventorysystemadvance')) {
                if (isset($data['currentwarehouse'])) {
                    $getCurWar = $data['currentwarehouse'];
                } else {
                    $getCurWar = '';
                }
                $this->inventorysystemadvanceHelper->updateInventory($getParams, $getCurWar);
            } else {
                $this->inventorysystemadvanceHelper->updateInventory($getParams);
            }
        } catch (\Magento\Framework\Model\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }
}
