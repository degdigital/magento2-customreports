<?php

namespace DEG\CustomReports\Test\Unit\Model\Config\Source;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Model\Config\Source\AutomatedExports;
use Magento\Framework\Api\SearchCriteriaBuilder;
use PHPUnit\Framework\TestCase;

class AutomatedExportsTest extends TestCase
{
    /**
     * @var AutomatedExports
     */
    protected $automatedExports;

    /**
     * @var SearchCriteriaBuilder|Mock
     */
    protected $searchCriteriaBuilder;

    /**
     * @var AutomatedExportRepositoryInterface|Mock
     */
    protected $automatedExportRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->automatedExportRepository = $this->createMock(AutomatedExportRepositoryInterface::class);
        $this->automatedExports = new AutomatedExports($this->searchCriteriaBuilder, $this->automatedExportRepository);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->automatedExports);
        unset($this->searchCriteriaBuilder);
        unset($this->automatedExportRepository);
    }

    public function testToOptionArray(): void
    {
        $searchCriteriaMock = $this->createMock(\Magento\Framework\Api\SearchCriteria::class);
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteriaMock);

        $searchResults = $this->createMock(\Magento\Framework\Api\SearchResultsInterface::class);
        $this->automatedExportRepository->method('getList')->willReturn($searchResults);

        $this->automatedExports->toOptionArray();
    }
}
