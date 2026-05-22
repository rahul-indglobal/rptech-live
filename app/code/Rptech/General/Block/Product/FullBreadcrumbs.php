<?php

namespace Rptech\General\Block\Product;

use Magento\Catalog\Helper\Data;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Registry;
use Magento\Framework\Api\AttributeValue;

class FullBreadcrumbs extends \Magento\Framework\View\Element\Template
{
    /**
     * Catalog data
     *
     * @var Data
     */
    private $catalogData = null;
    private $registry;
    private $categoryCollection;
    private $categoryFactory;

    /**
     * @param Context $context
     * @param Data $catalogData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $catalogData,
        CategoryFactory $categoryFactory,
        Registry $registry,
        CollectionFactory $categoryCollection,
        array $data = []
    )
    {
        $this->catalogData = $catalogData;
        $this->categoryFactory = $categoryFactory;
        $this->registry = $registry;
        $this->categoryCollection = $categoryCollection;
        parent::__construct($context, $data);
    }

    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    public function getCategory($categoryId)
    {
        $this->category = $this->categoryFactory->create();
        $this->category->load($categoryId);
        return $this->category;
    }

    public function getCategoryProductIds($product)
    {
        /** @var  $categoryIds  AttributeValue */
        $categoriesIds = [];
        $categoryIds = $product->getCategoryIds();
        foreach ($categoryIds as $categoryId) {
            $categoryData = $this->getCategory($categoryId);
            $parentCategories = $categoryData->getparent_id();
            foreach ($categoryData->getParentCategories() as $parent) {
                array_push($categoriesIds, $parent->getId());
            }
        }
        return $categoriesIds;
    }

    public function getFilteredCollection($categoryIds)
    {
        $collection = $this->categoryCollection->create();
        $filtered_colection = $collection
            ->addFieldToSelect('*')
            ->addFieldToFilter(
                'entity_id',
                ['in' => $categoryIds]
            )
            ->setOrder('level', 'ASC')
            ->load();
        return $filtered_colection;
    }

    public function getCategories($filtered_colection)
    {
        $categories = '';
        foreach ($filtered_colection as $categoriesData) {
            $categories .= '<li class="item category' . $categoriesData->getId() . '">';
            $categories .= '<a href="' . $categoriesData->getUrl() . '" title="' . $categoriesData->getData('name') . '">';
            $categories .= $categoriesData->getData('name') . '</a>';
            $categories .= '</li>';
        }
        return $categories;
    }

    public function getProductBreadcrumbs()
    {
        $product = $this->getProduct();
        $categoryIds = $this->getCategoryProductIds($product);

        $filtered_colection = $this->getFilteredCollection($categoryIds);

        $categories = $this->getCategories($filtered_colection);

        $home_url = '<li class="item home"><a href="' . $this->_storeManager->getStore()->getBaseUrl() . '" title="' . __("Home") . '">' . __("Home") . '</a></li>';
        $product_name = '<li class="item product"><strong>' . $product->getName() . '</strong></li>';
        return $home_url . $categories . $product_name;
    }
}
