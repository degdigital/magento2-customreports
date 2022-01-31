<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

/** @noinspection MessDetectorValidationInspection */

namespace DEG\CustomReports\Test\Unit\Model;

use ArrayObject;
use DEG\CustomReports\Model\CustomReportFactory;
use DEG\CustomReports\Model\CustomReportRepository;
use DEG\CustomReports\Model\ResourceModel\CustomReport;
use DEG\CustomReports\Model\ResourceModel\CustomReport\Collection;
use DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\DataObject;
use PHPUnit\Framework\TestCase;

class CustomReportRepositoryTest extends TestCase
{
    /**
     * @var CustomReportRepository
     */
    protected CustomReportRepository $customReportRepository;

    /**
     * @var CustomReportFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customReportFactory;

    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchResultsFactory;

    /**
     * @var CustomReport|\PHPUnit\Framework\MockObject\MockObject
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
        $this->customReportRepository = new CustomReportRepository(
            $this->customReportFactory,
            $this->collectionFactory,
            $this->searchResultsFactory,
            $this->customReportResource
        );
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

    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function testSave(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\CustomReport::class);

        $this->customReportRepository->save($model);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteById(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\CustomReport::class);
        $this->customReportFactory->method('create')->willReturn($model);
        $model->method('getId')->willReturn(1);

        $this->customReportRepository->deleteById(1);
    }

    public function testGetList(): void
    {
        $criteriaMock = $this->createMock(SearchCriteriaInterface::class);

        $searchMock = $this->createMock(SearchResultsInterface::class);
        $this->searchResultsFactory->method('create')->willReturn($searchMock);

        $filterGroup = $this->createMock(FilterGroup::class);
        $criteriaMock->method('getFilterGroups')->willReturn([$filterGroup]);

        $filter = $this->createMock(Filter::class);
        $filterGroup->method('getFilters')->willReturn([$filter]);

        $collectionMock = $this->createMock(Collection
        ::class);
        $this->collectionFactory->method('create')->willReturn($collectionMock);

        $sortOrdersMock = $this->createMock(SortOrder::class);
        $criteriaMock->method('getSortOrders')->willReturn([$sortOrdersMock]);

        $collectionMock->method('getIterator')
            ->willReturn(new ArrayObject([$this->createMock(DataObject::class)]));

        $this->customReportRepository->getList($criteriaMock);
    }
}
