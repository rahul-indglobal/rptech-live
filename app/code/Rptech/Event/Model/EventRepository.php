<?php

namespace Rptech\Event\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rptech\Event\Api\EventRepositoryInterface;
use Rptech\Event\Api\Data\EventInterface;
use Rptech\Event\Api\Data\EventInterfaceFactory;
use Rptech\Event\Api\Data\EventSearchResultsInterfaceFactory;
use Rptech\Event\Api\Data\EventSearchResultsInterface;
use Rptech\Event\Model\ResourceModel\Event as ResourceData;
use Rptech\Event\Model\Event;
use Rptech\Event\Model\ResourceModel\Event\CollectionFactory as DataCollectionFactory;

/**
 * Class EventRepository
 * @package Rptech\Event\Model
 */
class EventRepository implements EventRepositoryInterface
{
    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var ResourceData
     */
    protected $resource;

    /**
     * @var DataCollectionFactory
     */
    protected $dataCollectionFactory;

    /**
     * @var EventSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var EventInterfaceFactory
     */
    protected $dataInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        ResourceData $resource,
        DataCollectionFactory $dataCollectionFactory,
        EventSearchResultsInterfaceFactory $dataSearchResultsInterfaceFactory,
        EventInterfaceFactory $dataInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->searchResultsFactory = $dataSearchResultsInterfaceFactory;
        $this->dataInterfaceFactory = $dataInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Save Event data
     *
     * @param EventInterface $data
     * @return EventInterface
     * @throws CouldNotSaveException
     */
    public function save(EventInterface $data)
    {
        try {
            /** @var EventInterfaceFactory|\Magento\Framework\Model\AbstractModel $data */
            $this->resource->save($data);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the data: %1',
                $exception->getMessage()
            ));
        }
        return $data;
    }

    /**
     * Get Event data by id
     *
     * @param int $id
     * @return Event
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        if (!isset($this->instances[$id])) {
            /** @var EventInterface|\Magento\Framework\Model\AbstractModel $data */
            $data = $this->dataInterfaceFactory->create();
            $this->resource->load($data, $id);
            if (!$data->getId()) {
                throw new NoSuchEntityException(__('Requested data doesn\'t exist'));
            }
            $this->instances[$id] = $data;
        }
        return $this->instances[$id];
    }

    /**
     * Get list of Event data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return EventSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var EventSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Rptech\Event\Model\ResourceModel\Event\Collection $collection */
        $collection = $this->dataCollectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            $collection->addOrder(EventInterface::KEY_ENTITY_ID, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $data = [];
        foreach ($collection as $datum) {
            $dataDataObject = $this->dataInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($dataDataObject, $datum->getData(), EventInterface::class);
            $data[] = $dataDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($data);
    }

    /**
     * Delete Event record
     *
     * @param EventInterface $data
     * @return bool
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function delete(EventInterface $data)
    {
        /** @var EventInterface|\Magento\Framework\Model\AbstractModel $data */
        $id = $data->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($data);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove data %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Delete Event record id
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws StateException
     * @throws CouldNotSaveException
     */
    public function deleteById($id)
    {
        $data = $this->getById($id);
        try {
            return $this->delete($data);
        } catch (CouldNotSaveException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (StateException $e) {
            throw new StateException(
                __('Unable to remove data %1', $id)
            );
        }
    }
}
