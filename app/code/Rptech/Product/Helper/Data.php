<?php
namespace Rptech\Product\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Catalog\Model\Product;

class Data extends AbstractHelper
{
	protected $_product;

	public function __construct(Product $product)
	{
		$this->_product = $product;
	}

	public function getProduct($id){
		return $this->_product->load($id);
	}
	
}