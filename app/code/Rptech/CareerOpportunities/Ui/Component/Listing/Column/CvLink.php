<?php

namespace Rptech\CareerOpportunities\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class CvLink
 * @package Rptech\CareerOpportunities\Ui\Component\Listing\Column
 */
class CvLink extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['cv_file']) && $item['cv_file'] != '') {
                    $item[$this->getData('name')] = '<a href="' . $item['cv_file'] . '" target="_blank">View Document</a>';
                }else{
                    $item[$this->getData('name')] = "No Document Found";
                }
            }
        }
        return $dataSource;
    }
}