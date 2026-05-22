<?php

namespace Rptech\FinancialReport\Model\FinancialReport;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rptech\FinancialReport\Model\FinancialReport;
use Rptech\FinancialReport\Model\FinancialReportFactory;
use Rptech\FinancialReport\Model\ResourceModel\FinancialReport\CollectionFactory;

/**
 * Class DataProvider
 * @package Rptech\FinancialReport\Model\FinancialReport
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var File
     */
    protected $fileInfo;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var FinancialReportFactory
     */
    protected $financialReportFactory;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param File $fileInfo
     * @param RequestInterface $request
     * @param FinancialReportFactory $financialReportFactory
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Rptech\FinancialReport\Model\FinancialReport\File $fileInfo,
        RequestInterface $request,
        FinancialReportFactory $financialReportFactory,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->fileInfo = $fileInfo;
        $this->request = $request;
        $this->financialReportFactory = $financialReportFactory;
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var FinancialReport $financialReport */
        foreach ($items as $financialReport) {
            $financialReportData = $financialReport->getData();
//            $financialReportData = $this->convertValues($financialReport, $financialReportData);
            $this->loadedData[$financialReport->getId()] = $financialReportData;
            $item['file_previews'] = [];
            foreach ($financialReport->getDocItems() as $document) {

            }
        }

        $data = $this->dataPersistor->get('financial_report');
        if (!empty($data)) {
            $award = $this->collection->getNewEmptyItem();
            $award->setData($data);
            $this->loadedData[$award->getId()] = $financialReport->getData();
            $this->dataPersistor->clear('financial_report');
        }
        return $this->loadedData;
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return [];
    }

    /**
     * @param $award
     * @param $awardData
     * @return mixed
     */
    private function convertValues($financialReport, $financialReportData)
    {
        /**
         * @var \Rptech\FinancialReport\Model\FinancialReport $financialReport
         * @var \Rptech\FinancialReport\Model\FinancialReport\File $fileInfo
         */
        $fileName = $financialReport->getData('document');

        $fileInfo = $this->getFileInfo();
        if ($fileName && $fileInfo->isFile($fileName)) {
            $stat = $fileInfo->getStat($fileName);
            $mime = $fileInfo->getMimeType($fileName);
            unset($financialReportData['document']);
            $financialReportData['document'][0]['name'] = basename($fileName);
            $financialReportData['document'][0]['url'] = $financialReport->getDocumentUrl();
            $financialReportData['document'][0]['size'] = isset($stat) ? $stat['size'] : 0;
            $financialReportData['document'][0]['type'] = $mime;
        } else {
            $financialReportData['document'] = null;
        }

        return $financialReportData;
    }

    /**
     * @return File
     */
    private function getFileInfo()
    {
        return $this->fileInfo;
    }
}