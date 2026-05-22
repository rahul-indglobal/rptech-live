<?php

namespace Rptech\Media\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Rptech\Media\Model\ResourceModel\Media\CollectionFactory;
use Magento\Framework\Registry;
use Rptech\Media\Helper\Data as Helper;

class Index extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $collection;
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Index constructor.
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Registry $registry,
        Helper $helper,
        array $data = []
    )
    {
        $this->collection = $collectionFactory;
        $this->registry = $registry;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Media Pagination'));
        if ($this->getMediaCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'media.index.pager'
            )->setAvailableLimit([20 => 20, 30 => 30, 40 => 40, 50 => 50])
                ->setShowPerPage(true)->setCollection(
                    $this->getMediaCollection()
                );
            $this->setChild('pager', $pager);
            $this->getMediaCollection()->load();
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMediaCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest(
        )->getParam('limit') : 20;
        $collection = $this->collection->create()->addFieldToFilter('is_active', 1);
        if ($this->getYearFilter()){
            $collection->addFieldToFilter('created_at', ['like' => '%'.$this->getYearFilter().'%']);
        }
        if ($this->getBrandFilter()){
            $collection->addFieldToFilter('brand_id', $this->getBrandFilter());
        }
        $collection->setOrder('created_at', 'DESC');
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return array
     */
    public function getYearCollection(){
        $collection = $this->collection->create()->setOrder('created_at', 'DESC');
        $collection = $collection->addExpressionFieldToSelect(
            'year',
            'YEAR({{created_at}})',
            'created_at'
        );
        $collection->getSelect()->group('year');
        $uniqueYears = [];
        $resultCollection = [];

        foreach ($collection as $item) {
            $year = $item->getData('year');
            if (!in_array($year, $uniqueYears)) {
                $uniqueYears[] = $year;
                $resultCollection[] = $item;
            }
        }
        return $uniqueYears;
    }

    /**
     * @return mixed|null
     */
    public function getYearFilter(){
        return $this->registry->registry('media_year');
    }

    public function getBrandFilter(){
        return $this->registry->registry('brand_id');
    }

    /**
     * @return mixed
     */
    public function getBrandCollection($brandId=""){
        if ($brandId) {
            return $this->helper->getBrandCollection()->addFieldToFilter('entity_id', $brandId)->getFirstItem();
        }
        return $this->helper->getBrandCollection();
    }



}
