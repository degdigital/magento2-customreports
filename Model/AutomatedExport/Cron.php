<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\DeleteDynamicCronInterface;
use DEG\CustomReports\Api\ExportReportServiceInterface;
use Exception;
use Magento\Cron\Model\Schedule;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class Cron
{
    public function __construct(
        protected AutomatedExportRepositoryInterface $automatedExportRepository,
        protected DeleteDynamicCronInterface $deleteDynamicCron,
        protected LoggerInterface $logger,
        protected ExportReportServiceInterface $exportReportService
    ) {
    }

    /**
     * @param Schedule $schedule
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Schedule $schedule): bool
    {
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
            $this->logger->critical('Cronjob exception for job_code ' . $jobCode . ': ' . $e->getMessage());
            throw $e;
        }

        return true;
    }
}
