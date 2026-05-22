<?php
namespace Rptech\Offerzone\Block\Offer;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  as CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CatCollectionFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Helper\Image;

class Zone extends \Magento\Framework\View\Element\Template
{
	protected $_collectionFactory;
	protected $_catCollectionFactory;
	protected $_listProduct;
	protected $_productImageHelper;


	public function __construct(Context $context, CollectionFactory $_collectionFactory, CatCollectionFactory $_catCollectionFactory, ListProduct $_listProduct, Image $_productImageHelper)
	{
		$this->_collectionFactory = $_collectionFactory;
		$this->_catCollectionFactory = $_catCollectionFactory;
		$this->_listProduct = $_listProduct;
		$this->_productImageHelper = $_productImageHelper;
		parent::__construct($context);
	}

	public function getProductCollection()
	{
		$_productCollection = $this->_collectionFactory->create();
		$_productCollection->addAttributeToSelect('*');
		$_productCollection->addAttributeToFilter('special_price', array('neq' => ""));
		$_productCollection->addAttributeToFilter(
		        array(
		            array('attribute' => 'today_s_deal', 'eq' => 1)
		        )
		);

		return $_productCollection;
	}

	public function getCategoryIds(){
		$categories_id = array();
		$categories = $this->_catCollectionFactory->create();
		$categories->addAttributeToSelect('*');
		foreach ($categories as $key => $category) {
			if ($key > 2)
		    $categories_id[$category->getId()] = $category->getName();
		}
		return $categories_id;
	}

	public function getCategoryProducts($catId){
		$collectionCateProducts = $this->_collectionFactory->create();
        $collectionCateProducts->addAttributeToSelect('*');
        $collectionCateProducts->addAttributeToFilter('special_price', array('neq' => ""));
        $collectionCateProducts->addCategoriesFilter(['in' => $catId]);
        return $collectionCateProducts;
	}

	public function getAddtoCartUrl($product){
		return $this->_listProduct->getAddToCartUrl($product);
	}

	public function resizeImage($product, $imageId, $width, $height = null){
        $resizedImage = $this->_productImageHelper
                           ->init($product, $imageId)
                           ->constrainOnly(TRUE)
                           ->keepAspectRatio(TRUE)
                           ->keepTransparency(TRUE)
                           ->keepFrame(FALSE)
                           ->resize($width, $height);
        return $resizedImage;
    }  

}