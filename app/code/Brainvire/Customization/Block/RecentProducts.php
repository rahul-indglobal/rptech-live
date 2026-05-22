<?php 

namespace Brainvire\Customization\Block;

use Magento\Store\Model\StoreManagerInterface;

class RecentProducts extends \Magento\Framework\View\Element\Template
{
    protected $recentlyViewed;
    protected $storeManager;
    protected $_stockItemRepository;
    protected $_priceCurrency;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Reports\Block\Product\Viewed $recentlyViewed,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockItemRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->recentlyViewed = $recentlyViewed;
        $this->storeManager = $storeManager;
        $this->_stockItemRepository = $stockItemRepository;
        $this->_priceCurrency = $priceCurrency;
        parent::__construct( $context, $data );
    }

    public function getMostRecentlyViewed(){
        return $this->recentlyViewed->getItemsCollection();
    }

    public function getStoreUrl()
    {
        return $this->storeManager->getStore()->getUrl();
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product';
        return $mediaUrl;
    }

    public function getStockItemBySku($productSku)
    {
        return $this->_stockItemRepository->getStockItemBySku($productSku);
    }

    public function getCurrencySymbol()
    {
        $this->storeManager->getStore()->getCurrentCurrency()->getSymbol();
    }

    public function getCurrentCurrencySymbol()
    {
      return $this->_priceCurrency->getCurrency()->getCurrencySymbol();
    }

}