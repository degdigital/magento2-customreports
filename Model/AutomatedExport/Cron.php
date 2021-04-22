<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Api\DeleteDynamicCronInterface;
use DEG\CustomReports\Block\Adminhtml\Report\Grid;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Exception;
use Magento\Cron\Model\Schedule;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

class Cron
{
    private $automatedExportRepository;
    private $deleteDynamicCron;
    private $resultPageFactory;
    private $currentCustomReportRegistry;
    private $customReportRepository;
    private $logger;

    /**
     * Cron constructor.
     *
     * @param \DEG\CustomReports\Api\AutomatedExportRepositoryInterface $automatedExportRepository
     * @param \DEG\CustomReports\Api\CustomReportRepositoryInterface    $customReportRepository
     * @param \DEG\CustomReports\Api\DeleteDynamicCronInterface         $deleteDynamicCron
     * @param \Magento\Framework\View\Result\PageFactory                $resultPageFactory
     * @param \DEG\CustomReports\Registry\CurrentCustomReport           $currentCustomReportRegistry
     * @param \Psr\Log\LoggerInterface                                  $logger
     */
    public function __construct(
        AutomatedExportRepositoryInterface $automatedExportRepository,
        CustomReportRepositoryInterface $customReportRepository,
        DeleteDynamicCronInterface $deleteDynamicCron,
        PageFactory $resultPageFactory,
        CurrentCustomReport $currentCustomReportRegistry,
        LoggerInterface $logger
    ) {
        $this->automatedExportRepository = $automatedExportRepository;
        $this->deleteDynamicCron = $deleteDynamicCron;
        $this->resultPageFactory = $resultPageFactory;
        $this->currentCustomReportRegistry = $currentCustomReportRegistry;
        $this->customReportRepository = $customReportRepository;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Cron\Model\Schedule $schedule
     *
     * @return bool
     */
    public function execute(Schedule $schedule)
    {
        /** @var $reportGrid \DEG\CustomReports\Block\Adminhtml\Report\Grid */
        /** @var $exportBlock \DEG\CustomReports\Block\Adminhtml\Report\Export */

        try {
            $jobCode = $schedule->getJobCode();
            preg_match('/automated_export_(\d+)/', $jobCode, $jobMatch);
            if (!isset($jobMatch[1])) {
                throw new LocalizedException(__('No profile ID found in job_code.'));
            }
            $automatedExportId = $jobMatch[1];
            $automatedExport = $this->automatedExportRepository->getById($automatedExportId);
            if (!$automatedExport->getId()) {
                $this->deleteDynamicCron->execute($jobCode);
                throw new LocalizedException(__('Automated Export ID %1 does not exist.', $automatedExportId));
            }

            $customReportIds = $automatedExport->getCustomreportIds();
            foreach ($customReportIds as $customReportId) {
                $customReport = $this->customReportRepository->getById($customReportId);
                $this->currentCustomReportRegistry->set($customReport);
                $resultPage = $this->resultPageFactory->create();
                $reportGrid = $resultPage->getLayout()->createBlock(Grid::class, 'report.grid');
                $exportBlock = $reportGrid->getChildBlock('grid.export');
                foreach ($automatedExport->getExportTypes() as $exportType) {
                    //@todo: Extract exporter logic to its own class
                    if ($exportType == 'local_file_drop') {
                        foreach ($automatedExport->getFileTypes() as $fileType) {
                            if ($fileType == 'csv') {
                                $response = $exportBlock->getCronCsvFile($customReport, $automatedExport);
                                if (isset($response['value'])) {
                                    $this->logger->info(__('Successfully exported var/%1 file', $response['value']));
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logger->critical('Cronjob exception for job_code '.$jobCode.': '.$e->getMessage());
        }

        return true;
    }
}
