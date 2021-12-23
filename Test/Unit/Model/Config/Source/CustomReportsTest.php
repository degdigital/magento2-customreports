<?php

namespace DEG\CustomReports\Test\Unit\Model\Config\Source;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Model\Config\Source\CustomReports;
use Magento\Framework\Api\SearchCriteriaBuilder;
use PHPUnit\Framework\TestCase;

class CustomReportsTest extends TestCase
{
    /**
     * @var CustomReports
     */
    protected $customReports;

    /**
     * @var CustomReportRepositoryInterface|Mock
     */
    protected $customReportRepository;

    /**
     * @var SearchCriteriaBuilder|Mock
     */
    protected $searchCriteriaBuilder;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->customReportRepository = $this->createMock(CustomReportRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->customReports = new CustomReports($this->customReportRepository, $this->searchCriteriaBuilder);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->customReports);
        unset($this->customReportRepository);
        unset($this->searchCriteriaBuilder);
    }

    public function testToOptionArray(): void
    {
        $searchCriteriaMock = $this->createMock(\Magento\Framework\Api\SearchCriteria::class);
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteriaMock);

        $searchResults = $this->createMock(\Magento\Framework\Api\SearchResultsInterface::class);
        $this->customReportRepository->method('getList')->willReturn($searchResults);

        $customReport = $this->createMock(\DEG\CustomReports\Model\CustomReport::class);
        $searchResults->method('getItems')->willReturn($customReport);

        $this->customReports->toOptionArray();
    }
}
