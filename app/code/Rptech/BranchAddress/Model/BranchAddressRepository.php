<?php

namespace Rptech\BranchAddress\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rptech\BranchAddress\Api\BranchAddressRepositoryInterface;
use Rptech\BranchAddress\Api\Data\BranchAddressInterface;
use Rptech\BranchAddress\Api\Data\BranchAddressInterfaceFactory;
use Rptech\BranchAddress\Api\Data\BranchAddressSearchResultsInterfaceFactory;
use Rptech\BranchAddress\Api\Data\BranchAddressSearchResultsInterface;
use Rptech\BranchAddress\Model\ResourceModel\BranchAddress as ResourceData;
use Rptech\BranchAddress\Model\BranchAddress;
use Rptech\BranchAddress\Model\ResourceModel\BranchAddress\CollectionFactory as DataCollectionFactory;

/**
 * Class BranchAddressRepository
 * @package Rptech\BranchAddress\Model
 */
class BranchAddressRepository implements BranchAddressRepositoryInterface
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
     * @var BranchAddressSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var BranchAddressInterfaceFactory
     */
    protected $dataInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        ResourceData $resource,
        DataCollectionFactory $dataCollectionFactory,
        BranchAddressSearchResultsInterfaceFactory $dataSearchResultsInterfaceFactory,
        BranchAddressInterfaceFactory $dataInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->searchResultsFactory = $dataSearchResultsInterfaceFactory;
        $this->dataInterfaceFactory = $dataInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Save branch address data
     *
     * @param BranchAddressInterface $data
     * @return BranchAddressInterface
     * @throws CouldNotSaveException
     */
    public function save(BranchAddressInterface $data)
    {
        try {
            /** @var BranchAddressInterface|\Magento\Framework\Model\AbstractModel $data */
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
     * Get branch address data by id
     *
     * @param int $id
     * @return BranchAddress
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        if (!isset($this->instances[$id])) {
            /** @var BranchAddressInterface|\Magento\Framework\Model\AbstractModel $data */
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
     * Get list of branch address data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return BranchAddressSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var BranchAddressSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Rptech\BranchAddress\Model\ResourceModel\BranchAddress\Collection $collection */
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
            $collection->addOrder(BranchAddressInterface::KEY_ENTITY_ID, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $data = [];
        foreach ($collection as $datum) {
            $dataDataObject = $this->dataInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($dataDataObject, $datum->getData(), BranchAddressInterface::class);
            $data[] = $dataDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($data);
    }

    /**
     * Delete branch address record
     *
     * @param BranchAddressInterface $data
     * @return bool
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function delete(BranchAddressInterface $data)
    {
        /** @var BranchAddressInterface|\Magento\Framework\Model\AbstractModel $data */
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
     * Delete branch address record id
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
