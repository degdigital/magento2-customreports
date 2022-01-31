<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

/** @noinspection MessDetectorValidationInspection */

namespace DEG\CustomReports\Test\Unit\Model;

use ArrayObject;
use DEG\CustomReports\Model\AutomatedExportFactory;
use DEG\CustomReports\Model\AutomatedExportRepository;
use DEG\CustomReports\Model\ResourceModel\AutomatedExport;
use DEG\CustomReports\Model\ResourceModel\AutomatedExport\Collection;
use DEG\CustomReports\Model\ResourceModel\AutomatedExport\CollectionFactory;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\DataObject;
use PHPUnit\Framework\TestCase;

class AutomatedExportRepositoryTest extends TestCase
{
    /**
     * @var AutomatedExportRepository
     */
    protected AutomatedExportRepository $automatedExportRepository;

    /**
     * @var AutomatedExportFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $automatedExportFactory;

    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchResultsFactory;

    /**
     * @var AutomatedExport|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $automatedExportResource;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->automatedExportFactory = $this->createMock(AutomatedExportFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);
        $this->automatedExportResource = $this->createMock(AutomatedExport::class);
        $this->automatedExportRepository = new AutomatedExportRepository(
            $this->automatedExportFactory,
            $this->collectionFactory,
            $this->searchResultsFactory,
            $this->automatedExportResource
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->automatedExportRepository);
        unset($this->automatedExportFactory);
        unset($this->collectionFactory);
        unset($this->searchResultsFactory);
        unset($this->automatedExportResource);
    }

    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function testSave(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\AutomatedExport::class);

        $this->automatedExportRepository->save($model);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteById(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\AutomatedExport::class);
        $this->automatedExportFactory->method('create')->willReturn($model);
        $model->method('getId')->willReturn(1);

        $this->automatedExportRepository->deleteById(1);
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

        $this->automatedExportRepository->getList($criteriaMock);
    }
}
