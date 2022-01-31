<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\DeleteDynamicCronInterface;
use DEG\CustomReports\Api\ExportReportServiceInterface;
use Exception;
use Magento\Cron\Model\Schedule;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Cron
{
    protected AutomatedExportRepositoryInterface $automatedExportRepository;
    protected DeleteDynamicCronInterface $deleteDynamicCron;
    protected LoggerInterface $logger;

    /**
     * @var \DEG\CustomReports\Api\ExportReportServiceInterface
     */
    protected ExportReportServiceInterface $exportReportService;

    /**
     * Cron constructor.
     *
     * @param \DEG\CustomReports\Api\AutomatedExportRepositoryInterface $automatedExportRepository
     * @param \DEG\CustomReports\Api\DeleteDynamicCronInterface         $deleteDynamicCron
     * @param \Psr\Log\LoggerInterface                                  $logger
     * @param \DEG\CustomReports\Api\ExportReportServiceInterface       $exportReportService
     */
    public function __construct(
        AutomatedExportRepositoryInterface $automatedExportRepository,
        DeleteDynamicCronInterface $deleteDynamicCron,
        LoggerInterface $logger,
        ExportReportServiceInterface $exportReportService
    ) {
        $this->automatedExportRepository = $automatedExportRepository;
        $this->deleteDynamicCron = $deleteDynamicCron;
        $this->logger = $logger;
        $this->exportReportService = $exportReportService;
    }

    /**
     * @param \Magento\Cron\Model\Schedule $schedule
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Schedule $schedule): bool
    {
        /** @var $reportGrid \DEG\CustomReports\Block\Adminhtml\Report\Grid */
        /** @var $exportBlock \DEG\CustomReports\Block\Adminhtml\Report\Export */

        $jobCode = $schedule->getJobCode();
        try {
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

            $this->exportReportService->exportAll($automatedExport);
        } catch (Exception $e) {
            $this->logger->critical('Cronjob exception for job_code '.$jobCode.': '.$e->getMessage());
            throw $e;
        }

        return true;
    }
}
