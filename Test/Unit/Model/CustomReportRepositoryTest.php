<?php

namespace DEG\CustomReports\Test\Unit\Model;

use DEG\CustomReports\Model\CustomReportFactory;
use DEG\CustomReports\Model\CustomReportRepository;
use DEG\CustomReports\Model\ResourceModel\CustomReport;
use DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\DataObject;
use PHPUnit\Framework\TestCase;

class CustomReportRepositoryTest extends TestCase
{
    /**
     * @var CustomReportRepository
     */
    protected $customReportRepository;

    /**
     * @var CustomReportFactory|Mock
     */
    protected $customReportFactory;

    /**
     * @var CollectionFactory|Mock
     */
    protected $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory|Mock
     */
    protected $searchResultsFactory;

    /**
     * @var CustomReport|Mock
     */
    protected $customReportResource;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->customReportFactory = $this->createMock(CustomReportFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);
        $this->customReportResource = $this->createMock(CustomReport::class);
        $this->customReportRepository = new CustomReportRepository($this->customReportFactory, $this->collectionFactory, $this->searchResultsFactory, $this->customReportResource);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->customReportRepository);
        unset($this->customReportFactory);
        unset($this->collectionFactory);
        unset($this->searchResultsFactory);
        unset($this->customReportResource);
    }

    public function testSave(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\CustomReport::class);

        $this->customReportRepository->save($model);
    }

    public function testDeleteById(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\CustomReport::class);
        $this->customReportFactory->method('create')->willReturn($model);
        $model->method('getId')->willReturn(1);

        $this->customReportRepository->deleteById(1);
    }


    public function testGetList(): void
    {
        $criteriaMock = $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class);

        $searchMock = $this->createMock(\Magento\Framework\Api\SearchResultsInterface::class);
        $this->searchResultsFactory->method('create')->willReturn($searchMock);

        $filterGroup = $this->createMock(\Magento\Framework\Api\Search\FilterGroup::class);
        $criteriaMock->method('getFilterGroups')->willReturn([$filterGroup]);

        $filter = $this->createMock(\Magento\Framework\Api\Filter::class);
        $filterGroup->method('getFilters')->willReturn([$filter]);

        $collectionMock = $this->createMock(\DEG\CustomReports\Model\ResourceModel\CustomReport\Collection
                                            ::class);
        $this->collectionFactory->method('create')->willReturn($collectionMock);

        $sortOrdersMock = $this->createMock(\Magento\Framework\Api\SortOrder::class);
        $criteriaMock->method('getSortOrders')->willReturn([$sortOrdersMock]);

        $collectionMock->method('getIterator')
            ->willReturn(new \ArrayObject([$this->createMock(DataObject::class)]));

        $this->customReportRepository->getList($criteriaMock);
    }
}
