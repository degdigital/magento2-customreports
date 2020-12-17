<?php
namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomReportRepository implements \DEG\CustomReports\Api\CustomReportRepositoryInterface
{
    /**
     * @var \DEG\CustomReports\Model\CustomReportFactory
     */
    protected $objectFactory;
    /**
     * @var \DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * CustomReportRepository constructor.
     *
     * @param \DEG\CustomReports\Model\CustomReportFactory                          $objectFactory
     * @param \DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory                  $searchResultsFactory
     */
    public function __construct(
        CustomReportFactory $objectFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->objectFactory        = $objectFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $object
     *
     * @return \DEG\CustomReports\Api\Data\CustomReportInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(CustomReportInterface $object)
    {
        try {
            $object->save();
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $object;
    }

    /**
     * @param $id
     *
     * @return \DEG\CustomReports\Model\CustomReport
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $object = $this->objectFactory->create();
        $object->load($id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }
        return $object;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $object
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(CustomReportInterface $object)
    {
        try {
            $object->delete();
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);
        return $searchResults;
    }
}
