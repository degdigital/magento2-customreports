<?php
declare(strict_types=1);

namespace DEG\CustomReports\Test\Unit\Model\Config\Source;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Model\Config\Source\AutomatedExports;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use PHPUnit\Framework\TestCase;

class AutomatedExportsTest extends TestCase
{
    /**
     * @var AutomatedExports
     */
    protected AutomatedExports $automatedExports;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchCriteriaBuilder;

    /**
     * @var AutomatedExportRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
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
        $searchCriteriaMock = $this->createMock(SearchCriteria::class);
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteriaMock);

        $searchResults = $this->createMock(SearchResultsInterface::class);
        $this->automatedExportRepository->method('getList')->willReturn($searchResults);

        $this->automatedExports->toOptionArray();
    }
}
