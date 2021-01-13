<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Model\ResourceModel\AutomatedExport\CollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class AutomatedExportRepository implements AutomatedExportRepositoryInterface
{
    /**
     * @var \DEG\CustomReports\Model\AutomatedExportFactory
     */
    protected $automatedExportFactory;
    /**
     * @var \DEG\CustomReports\Model\ResourceModel\AutomatedExport\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Magento\Framework\Api\SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;
    /**
     * @var \DEG\CustomReports\Model\ResourceModel\AutomatedExport
     */
    private $automatedExportResource;

    /**
     * AutomatedExportRepository constructor.
     *
     * @param \DEG\CustomReports\Model\AutomatedExportFactory                          $automatedExportFactory
     * @param \DEG\CustomReports\Model\ResourceModel\AutomatedExport\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory                  $searchResultsFactory
     * @param \DEG\CustomReports\Model\ResourceModel\AutomatedExport                   $automatedExportResource
     */
    public function __construct(
        AutomatedExportFactory $automatedExportFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        \DEG\CustomReports\Model\ResourceModel\AutomatedExport $automatedExportResource
    ) {
        $this->automatedExportFactory = $automatedExportFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->automatedExportResource = $automatedExportResource;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface|\DEG\CustomReports\Model\AutomatedExport $automatedExport
     *
     * @return \DEG\CustomReports\Api\Data\AutomatedExportInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(AutomatedExportInterface $automatedExport): AutomatedExportInterface
    {
        try {
            $this->automatedExportResource->save($automatedExport);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $automatedExport;
    }

    /**
     * @param $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface|\DEG\CustomReports\Model\AutomatedExport $automatedExport
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(AutomatedExportInterface $automatedExport): bool
    {
        try {
            $this->automatedExportResource->delete($automatedExport);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @param $id
     *
     * @return \DEG\CustomReports\Model\AutomatedExport
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id): AutomatedExport
    {
        $automatedExport = $this->automatedExportFactory->create();
        $this->automatedExportResource->load($automatedExport, $id);
        if (!$automatedExport->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }

        return $automatedExport;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface
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
