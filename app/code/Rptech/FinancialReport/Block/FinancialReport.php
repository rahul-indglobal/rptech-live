<?php

namespace Rptech\FinancialReport\Block;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Rptech\FinancialReport\Model\ResourceModel\FinancialReport\CollectionFactory;
use Rptech\FinancialReport\Model\FinancialReportFactory;

class FinancialReport extends \Magento\Framework\View\Element\Template
{
    const DOCUMENT_PATH = "financialreport/documents/";
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var FinancialReportFactory
     */
    protected $financialReportFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ServiceAddress constructor.
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        FinancialReportFactory $financialReportFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->financialReportFactory = $financialReportFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function getFinancialReportCollection(){
        return $collection = $this->collectionFactory->create();
    }

    public function getMediaPath(){

        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).self::DOCUMENT_PATH;
    }
}