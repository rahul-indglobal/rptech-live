<?php

namespace Rptech\Awards\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class Thumbnail
 * @package Rptech\Awards\Ui\Component\Listing\Column
 */
class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'thumbnail';

    const ALT_FIELD = 'title';

    /**
     * @var \Rptech\Awards\Model\Awards\Image
     */
    protected $imageHelper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Thumbnail constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Rptech\Awards\Model\Awards\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Rptech\Awards\Model\Awards\Image $imageHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $filename = $item['image'];
                $item["image"] = $this->getImageHtml($this->imageHelper->getBaseUrl() . $filename);
            }
        }
        return $dataSource;
    }

    /**
     * Get image html
     *
     * @param string $imageUrl
     * @return string
     */
    public function getImageHtml($imageUrl){
        return '<img src="'.$imageUrl.'" alt="" style="max-width:none; width:100px">';
    }
}