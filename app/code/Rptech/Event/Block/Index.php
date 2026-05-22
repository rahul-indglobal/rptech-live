<?php

namespace Rptech\Event\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Rptech\Event\Model\ResourceModel\Event\CollectionFactory;
use Magento\Framework\Registry;

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
     * Index constructor.
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Registry $registry,
        array $data = []
    )
    {
        $this->collection = $collectionFactory;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Event Pagination'));
        if ($this->getEventCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'event.index.pager'
            )->setAvailableLimit([20 => 20, 30 => 30, 40 => 40, 50 => 50])
                ->setShowPerPage(true)->setCollection(
                    $this->getEventCollection()
                );
            $this->setChild('pager', $pager);
            $this->getEventCollection()->load();
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 20;
        /**
         * @var \Rptech\Event\Model\ResourceModel\Event\Collection $collection
         */
        $collection = $this->collection->create();
        $collection->addFieldToFilter('is_active', 1);
        if ($this->getYearFilter()) {
            $collection->addFieldToFilter('publish_date', ['like' => '%' . $this->getYearFilter() . '%']);
        }
        $collection->setOrder('publish_date', "DESC");
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
     * @param $param
     * @return string
     */
    public function getEachUrl($param)
    {
        return $this->getUrl('events/details/', ['id' => $param]);
    }

    /**
     * @return array
     */
    public function getYearCollection()
    {
        $collection = $this->collection->create();
        $collection = $collection->addExpressionFieldToSelect(
            'year',
            'YEAR({{publish_date}})',
            'publish_date'
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
        rsort($uniqueYears);
        return $uniqueYears;
    }

    /**
     * @return mixed|null
     */
    public function getYearFilter()
    {
        return $this->registry->registry('year');
    }

    /**
     * @param $event
     * @return false|string
     */
    public function getPublishDate($event)
    {
        if (!empty($event->getPublishDate())) {
            return date('F Y', strtotime($event->getPublishDate()));
        }
        return date('F Y', strtotime($event->getCreatedAt()));
    }

}
