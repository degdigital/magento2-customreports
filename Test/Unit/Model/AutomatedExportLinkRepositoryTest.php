<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

/** @noinspection MessDetectorValidationInspection */

namespace DEG\CustomReports\Test\Unit\Model;

use ArrayObject;
use DEG\CustomReports\Model\AutomatedExportLinkFactory;
use DEG\CustomReports\Model\AutomatedExportLinkRepository;
use DEG\CustomReports\Model\ResourceModel\AutomatedExportLink;
use DEG\CustomReports\Model\ResourceModel\AutomatedExportLink\Collection;
use DEG\CustomReports\Model\ResourceModel\AutomatedExportLink\CollectionFactory;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\DataObject;
use PHPUnit\Framework\TestCase;

class AutomatedExportLinkRepositoryTest extends TestCase
{
    /**
     * @var AutomatedExportLinkRepository
     */
    protected AutomatedExportLinkRepository $automatedExportLinkRepository;

    /**
     * @var AutomatedExportLinkFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $automatedExportLinkFactory;

    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchResultsFactory;

    /**
     * @var AutomatedExportLink|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $automatedExportLinkResource;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->automatedExportLinkFactory = $this->createMock(AutomatedExportLinkFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);
        $this->automatedExportLinkResource = $this->createMock(AutomatedExportLink::class);
        $this->automatedExportLinkRepository = new AutomatedExportLinkRepository(
            $this->automatedExportLinkFactory,
            $this->collectionFactory,
            $this->searchResultsFactory,
            $this->automatedExportLinkResource
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->automatedExportLinkRepository);
        unset($this->automatedExportLinkFactory);
        unset($this->collectionFactory);
        unset($this->searchResultsFactory);
        unset($this->automatedExportLinkResource);
    }

    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function testSave(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\AutomatedExportLink::class);

        $this->automatedExportLinkRepository->save($model);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteById(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\AutomatedExportLink::class);
        $this->automatedExportLinkFactory->method('create')->willReturn($model);
        $model->method('getId')->willReturn(1);

        $this->automatedExportLinkRepository->deleteById(1);
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

        $this->automatedExportLinkRepository->getList($criteriaMock);
    }
}
