<?php

namespace Brainvire\Productdetail\Observer;

class LayoutLoadBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
	protected $categoryCollectionFactory;
	protected $categoryRepository;

    public function __construct(
		\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
		\Magento\Catalog\Model\CategoryRepository $categoryRepository,
       \Magento\Framework\Registry $registry
    ){
		$this->categoryCollectionFactory = $categoryCollectionFactory;
		$this->categoryRepository = $categoryRepository;
        $this->registry = $registry;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$projCatIds = array();
		$levels = 2;
        $product = $this->registry->registry('current_product');

        if (!$product) {
          return $this;
        }
		$projCategoryId = 375;
		$category = $this->categoryRepository->get($projCategoryId);
    	if ((int)$levels < 1) {
        	$levels = 1;
    	}
    	$collection = $this->categoryCollectionFactory->create()
          ->addPathsFilter($category->getPath().'/') 
          ->addLevelFilter($category->getLevel() + $levels);
		$allProjIds = $collection->getAllIds();
		$allProjIds[] = 375;
		
		$cats = $product->getCategoryIds();
		$result = array_intersect($cats, $allProjIds);
		
        if (!empty($result)) { // your condition
           $layout = $observer->getLayout();
           $productId = $product->getId();
			$layout->getUpdate()->addHandle("custom_project_detail");
        }
		return $this;
    }
}