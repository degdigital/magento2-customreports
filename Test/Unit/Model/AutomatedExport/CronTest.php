<?php
declare(strict_types=1);
/** @noinspection MessDetectorValidationInspection */

namespace DEG\CustomReports\Test\Unit\Model\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Api\DeleteDynamicCronInterface;
use DEG\CustomReports\Api\ExportReportServiceInterface;
use DEG\CustomReports\Model\AutomatedExport;
use DEG\CustomReports\Model\AutomatedExport\Cron;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Cron\Model\Schedule;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CronTest extends TestCase
{
    /**
     * @var Cron
     */
    protected Cron $cron;

    /**
     * @var AutomatedExportRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $automatedExportRepository;

    /**
     * @var CustomReportRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customReportRepository;

    /**
     * @var DeleteDynamicCronInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $deleteDynamicCron;

    /**
     * @var PageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $resultPageFactory;

    /**
     * @var CurrentCustomReport|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $currentCustomReportRegistry;

    /**
     * @var LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $logger;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->automatedExportRepository = $this->createMock(AutomatedExportRepositoryInterface::class);
        $this->customReportRepository = $this->createMock(CustomReportRepositoryInterface::class);
        $this->deleteDynamicCron = $this->createMock(DeleteDynamicCronInterface::class);
        $this->resultPageFactory = $this->createMock(PageFactory::class);
        $this->exportReportService = $this->createMock(ExportReportServiceInterface::class);
        $this->currentCustomReportRegistry = $this->createMock(CurrentCustomReport::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->cron = new Cron(
            $this->automatedExportRepository,
            $this->deleteDynamicCron,
            $this->logger,
            $this->exportReportService
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->cron);
        unset($this->automatedExportRepository);
        unset($this->customReportRepository);
        unset($this->deleteDynamicCron);
        unset($this->resultPageFactory);
        unset($this->currentCustomReportRegistry);
        unset($this->logger);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testExecute(): void
    {
        $scheduleMock = $this->getMockBuilder(Schedule::class)
            ->setMethods(['getJobCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $scheduleMock->method('getJobCode')->willReturn('automated_export_2');

        $automatedExportMock = $this->getMockBuilder(AutomatedExport::class)
            ->setMethods(['getId', 'getCustomreportIds', 'getExportTypes', 'getFileTypes'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->automatedExportRepository->method('getById')->willReturn($automatedExportMock);

        $automatedExportMock->method('getId')->willReturn(1);

        $automatedExportMock->method('getCustomreportIds')->willReturn([1]);

        $resultPageMock = $this->createMock(Page::class);
        $this->resultPageFactory->method('create')->willReturn($resultPageMock);

        $layoutMock = $this->getMockBuilder(AbstractBlock::class)->setMethods(['getChildBlock', 'createBlock'])
            ->disableOriginalConstructor()->getMock();
        $resultPageMock->method('getLayout')->willReturn($layoutMock);

        $layoutMock->method('createBlock')->willReturn($layoutMock);

        $layoutMock->method('getChildBlock')->willReturn($layoutMock);

        $automatedExportMock->method('getExportTypes')->willReturn(['local_file_drop']);

        $automatedExportMock->method('getFileTypes')->willReturn(['csv']);

        $this->cron->execute($scheduleMock);
    }
}
