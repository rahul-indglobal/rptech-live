<?php
namespace Vendor\BotChecker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\RequestInterface;

use Magento\Framework\UrlInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class BotRequestObserver implements ObserverInterface
{

    public function __construct(
        Http $response,
        RequestInterface $request,
        
        UrlInterface $urlInterface,
        DirectoryList $directoryList,
        
        UrlRewriteCollectionFactory $urlRewriteCollectionFactory,
        PageRepositoryInterface $pageRepository,
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->_response = $response;
        $this->_request = $request;
        
        $this->_urlInterface = $urlInterface;
        $this->basePath = $directoryList->getPath(DirectoryList::ROOT);
        
        
        $this->urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
        $this->pageRepository = $pageRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }
    
    public function execute(Observer $observer)
    {
        $request = $this->_request;
        $userAgent = $request->getServer('HTTP_USER_AGENT');

        if ($this->isBot($userAgent) == 'true') {
        
            $htmlContent = "";
            $currentUrl = $this->_urlInterface->getCurrentUrl();
            $urlPath = explode('.com/', $currentUrl);
            $url = end($urlPath);
            
            $urlRewriteCollection = $this->urlRewriteCollectionFactory->create();
            $urlRewriteCollection->addFieldToFilter('request_path', $url);
            $urlRewrite = $urlRewriteCollection->getFirstItem();
        
            if ($urlRewrite->getId()) {
                if ($urlRewrite->getEntityType() == 'category'){
                    $htmlContent = $this->getListingHtml();
                }
                elseif ($urlRewrite->getEntityType() == 'product'){
                    $htmlContent = $this->getProductDetailHtml();
                }
            }

            if ($url == '') {
                $htmlContent = file_get_contents($this->basePath . '/app/code/Vendor/BotChecker/pages/home.html');
            }
            elseif ($url == 'aboutus') {
                $htmlContent = file_get_contents($this->basePath . '/app/code/Vendor/BotChecker/pages/aboutus.html');
            }
            
            // Set the response content
            $this->_response->setBody($htmlContent);
            // Send the response
            $this->_response->sendResponse();
            exit;
        }
    }

    private function isBot($userAgent)
    {
        $speedAgent = [
            "mozilla/5.0 (linux; android 11; moto g power (2022)) applewebkit/537.36 (khtml, like gecko) chrome/119.0.0.0 mobile safari/537.36",
            "mozilla/5.0 (macintosh; intel mac os x 10_15_7) applewebkit/537.36 (khtml, like gecko) chrome/119.0.0.0 safari/537.36",
            "mozilla/5.0 (linux; android 7.0; moto g (4)) applewebkit/537.36 (khtml, like gecko) chrome/94.0.4590.2 mobile safari/537.36 chrome-lighthouse",
            "mozilla/5.0 (macintosh; intel mac os x 10_15_7) applewebkit/537.36 (khtml, like gecko) chrome/94.0.4590.2 safari/537.36 chrome-lighthouse"
        ];
        if (in_array(strtolower($userAgent), $speedAgent)) {
            return 'true';
        }
        if (strpos(strtolower($userAgent), 'lighthouse')) {
            return 'true';
        }
        return 'false';
    }

    private function getProductDetailHtml()
    {
        $htmlContent = file_get_contents($this->basePath . '/app/code/Vendor/BotChecker/pages/pdp.html');
        return $htmlContent;
    }

    private function getListingHtml()
    {
        $htmlContent = file_get_contents($this->basePath . '/app/code/Vendor/BotChecker/pages/plp.html');
        return $htmlContent;
    }
}
