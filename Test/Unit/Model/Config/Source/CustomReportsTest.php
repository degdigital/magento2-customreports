<?php
declare(strict_types=1);

namespace DEG\CustomReports\Test\Unit\Model\Config\Source;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Model\Config\Source\CustomReports;
use DEG\CustomReports\Model\CustomReport;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use PHPUnit\Framework\TestCase;

class CustomReportsTest extends TestCase
{
    /**
     * @var CustomReports
     */
    protected CustomReports $customReports;

    /**
     * @var CustomReportRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customReportRepository;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit\Framework\MockObject\MockObject
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
        $searchCriteriaMock = $this->createMock(SearchCriteria::class);
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteriaMock);

        $searchResults = $this->createMock(SearchResultsInterface::class);
        $this->customReportRepository->method('getList')->willReturn($searchResults);

        $customReport = $this->createMock(CustomReport::class);
        $searchResults->method('getItems')->willReturn($customReport);

        $this->customReports->toOptionArray();
    }
}
