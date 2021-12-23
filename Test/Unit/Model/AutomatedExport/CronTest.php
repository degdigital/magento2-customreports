<?php

namespace DEG\CustomReports\Test\Unit\Model\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Api\DeleteDynamicCronInterface;
use DEG\CustomReports\Model\AutomatedExport\Cron;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CronTest extends TestCase
{
    /**
     * @var Cron
     */
    protected $cron;

    /**
     * @var AutomatedExportRepositoryInterface|Mock
     */
    protected $automatedExportRepository;

    /**
     * @var CustomReportRepositoryInterface|Mock
     */
    protected $customReportRepository;

    /**
     * @var DeleteDynamicCronInterface|Mock
     */
    protected $deleteDynamicCron;

    /**
     * @var PageFactory|Mock
     */
    protected $resultPageFactory;

    /**
     * @var CurrentCustomReport|Mock
     */
    protected $currentCustomReportRegistry;

    /**
     * @var LoggerInterface|Mock
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
        $this->currentCustomReportRegistry = $this->createMock(CurrentCustomReport::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->cron = new Cron($this->automatedExportRepository, $this->customReportRepository, $this->deleteDynamicCron, $this->resultPageFactory, $this->currentCustomReportRegistry, $this->logger);
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

    public function testExecute(): void
    {
        $scheduleMock = $this->getMockBuilder(\Magento\Cron\Model\Schedule::class)
            ->setMethods(['getJobCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $scheduleMock->method('getJobCode')->willReturn('automated_export_2');

        $automatedExportMock = $this->getMockBuilder(\DEG\CustomReports\Model\AutomatedExport::class)
            ->setMethods(['getId', 'getCustomreportIds', 'getExportTypes', 'getFileTypes'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->automatedExportRepository->method('getById')->willReturn($automatedExportMock);

        $automatedExportMock->method('getId')->willReturn(1);

        $automatedExportMock->method('getCustomreportIds')->willReturn([1]);

        $resultPageMock = $this->createMock(\Magento\Framework\View\Result\Page::class);
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
