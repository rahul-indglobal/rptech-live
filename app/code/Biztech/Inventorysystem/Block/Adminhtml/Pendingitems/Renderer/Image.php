<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\Adminhtml\Pendingitems\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Catalog\Helper\Image as CatalogImageHelper;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class Image extends AbstractRenderer
{
    protected $productModel;
    protected $storeManager;
    protected $imageHelper;

    /**
     * @param Context               $context
     * @param Product               $productModel
     * @param StoreManagerInterface $storeManager
     * @param CatalogImageHelper    $imageHelper
     */
    public function __construct(
        Context $context,
        Product $productModel,
        StoreManagerInterface $storeManager,
        CatalogImageHelper $imageHelper
    ) {
        $this->productModel = $productModel;
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        parent::__construct($context);
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function render(DataObject $row)
    {
        if ($row->getData('thumbnail') == "") {
            return 'No Image';
        } else {
            $product = $this->productModel->load($row->getData('entity_id'));
            $imageUrl = $this->imageHelper->init($product, 'product_listing_thumbnail');
            $imageUrl
                ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                ->setImageFile($product->getImage());

            $productImage = $imageUrl->getUrl();

            $width = $imageUrl->getWidth() ? $imageUrl->getWidth() : 90;
            $height = $imageUrl->getHeight() ? $imageUrl->getHeight() : 90;

            return '<img class="admin__control-thumbnail" src="' . $productImage . '" width="' . $width . '" height="' . $height . '" alt="' . $imageUrl->getLabel() . '"/>';
        }

        return parent::render($row);
    }
}
