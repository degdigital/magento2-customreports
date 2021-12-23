<?php

namespace DEG\CustomReports\Test\Unit\Model;

use DEG\CustomReports\Model\AutomatedExportLinkFactory;
use DEG\CustomReports\Model\AutomatedExportLinkRepository;
use DEG\CustomReports\Model\ResourceModel\AutomatedExportLink;
use DEG\CustomReports\Model\ResourceModel\AutomatedExportLink\CollectionFactory;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\DataObject;
use PHPUnit\Framework\TestCase;

class AutomatedExportLinkRepositoryTest extends TestCase
{
    /**
     * @var AutomatedExportLinkRepository
     */
    protected $automatedExportLinkRepository;

    /**
     * @var AutomatedExportLinkFactory|Mock
     */
    protected $automatedExportLinkFactory;

    /**
     * @var CollectionFactory|Mock
     */
    protected $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory|Mock
     */
    protected $searchResultsFactory;

    /**
     * @var AutomatedExportLink|Mock
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
        $this->automatedExportLinkRepository = new AutomatedExportLinkRepository($this->automatedExportLinkFactory, $this->collectionFactory, $this->searchResultsFactory, $this->automatedExportLinkResource);
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

    public function testSave(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\AutomatedExportLink::class);

        $this->automatedExportLinkRepository->save($model);
    }

    public function testDeleteById(): void
    {
        $model = $this->createMock(\DEG\CustomReports\Model\AutomatedExportLink::class);
        $this->automatedExportLinkFactory->method('create')->willReturn($model);
        $model->method('getId')->willReturn(1);

        $this->automatedExportLinkRepository->deleteById(1);
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

        $collectionMock = $this->createMock(\DEG\CustomReports\Model\ResourceModel\AutomatedExportLink\Collection
                                            ::class);
        $this->collectionFactory->method('create')->willReturn($collectionMock);

        $sortOrdersMock = $this->createMock(\Magento\Framework\Api\SortOrder::class);
        $criteriaMock->method('getSortOrders')->willReturn([$sortOrdersMock]);

        $collectionMock->method('getIterator')
            ->willReturn(new \ArrayObject([$this->createMock(DataObject::class)]));

        $this->automatedExportLinkRepository->getList($criteriaMock);
    }
}
