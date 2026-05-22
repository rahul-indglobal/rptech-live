<?php

namespace Rptech\FinancialReport\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Rptech\FinancialReport\Model\ResourceModel\FinancialReportDoc\CollectionFactory as ReportDocumentCollectionFactory;

/**
 * Class ImageBlock
 * @package Rptech\FinancialReport\Block\Adminhtml
 */
class ImageBlock extends Template
{
    const DOCUMENT_PATH = "financialreport/documents/";
    protected $_template = "images.phtml";
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var ReportDocumentCollectionFactory
     */
    protected $reportDocumentsCollection;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ImageBlock constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param ReportDocumentCollectionFactory $reportDocumentsCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        ReportDocumentCollectionFactory $reportDocumentsCollectionFactory,
        StoreManagerInterface $storeManager,
        array $data = [])
    {
        $this->coreRegistry = $registry;
        $this->reportDocumentsCollection = $reportDocumentsCollectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed|null
     */
    public function getFinancialReportData(){
        return $this->coreRegistry->registry('financial_report');
    }

    /**
     * @param $entityId
     * @return mixed
     */
    public function getFinancialReportDocsData($entityId){
        $collection = $this->reportDocumentsCollection->create();
        $collection = $collection->addFieldToFilter('parent_id', $entityId);
        return $collection;
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getMediaPath()
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).self::EVENT_IMAGE_PATH;

    }
}