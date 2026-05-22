<?php

namespace Rptech\Leadership\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class Thumbnail
 * @package Rptech\Leadership\Ui\Component\Listing\Columns
 */
class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'thumbnail';

    const ALT_FIELD = 'title';

    /**
     * @var \Rptech\Leadership\Model\Leadership\Image
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
     * @param \Rptech\Leadership\Model\Leadership\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Rptech\Leadership\Model\Leadership\Image $imageHelper,
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
                $item["image_url"] = $this->getImageHtml($this->imageHelper->getBaseUrl() . $filename);
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
        return '<img src="'.$imageUrl.'" alt="" style="max-width:none; width:200px">';
    }
}