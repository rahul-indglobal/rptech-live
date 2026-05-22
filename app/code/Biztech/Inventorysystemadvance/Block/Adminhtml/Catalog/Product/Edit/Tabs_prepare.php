<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Catalog\Product\Edit;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\UrlInterface;

class Tabs extends \Magento\Ui\Component\Form\Element\DataType\Text
{
    protected $inventorysystemHelper;
    protected $request;
    protected $layout;
    protected $urlBuilder;
    protected $_productModel;
    
    /**
     * @param ContextInterface                        $context
     * @param \Biztech\Inventorysystem\Helper\Data    $inventorysystemHelper
     * @param \Magento\Framework\App\Request\Http     $request
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Catalog\Model\Product          $productModel
     * @param array                                   $components
     * @param array                                   $data
     * @param UrlInterface                            $urlBuilder
     */
    public function __construct(
        ContextInterface $context,
        \Biztech\Inventorysystem\Helper\Data $inventorysystemHelper,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Catalog\Model\Product $productModel,
        array $components = [],
        array $data = [],
        UrlInterface $urlBuilder
    ) {
        $this->inventorysystemHelper = $inventorysystemHelper;
        $this->request = $request;
        $this->layout = $layout;
         $this->urlBuilder = $urlBuilder;
         $this->_productModel = $productModel;
        parent::__construct($context, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        $websites = $this->inventorysystemHelper->getAllWebsites();

        $product = $this->_productModel->load($this->request->getParam('id'));

        $getProdWebsites = $product->getWebsiteIds();
        $websiteExist = array_values(array_intersect($websites, $getProdWebsites));

        if (!empty($websiteExist)) {
            $mycustomblock = $this->layout->createBlock("Biztech\Inventorysystemadvance\Block\Adminhtml\Catalog\Product\Edit\Tab\Content")->setTemplate("Biztech_Inventorysystemadvance::inventorysystemadvance/warehouse/product/warehousegrid.phtml")->toHtml();
                        
            foreach ($dataSource['data'] as &$item) {
                if (isset($item['sku'])) {
                    $item[$this->getData('name')] = html_entity_decode($mycustomblock);
                }
            }
            return $dataSource;
        }
    }
}
