<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Service;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Api\ExportReportServiceInterface;
use DEG\CustomReports\Api\SendErrorEmailServiceInterface;
use DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerPoolInterface;
use Exception;
use Psr\Log\LoggerInterface;

class ExportReportService implements ExportReportServiceInterface
{
    public function __construct(
        protected CustomReportRepositoryInterface $customReportRepository,
        protected CustomReportManagementInterface $customReportManagement,
        protected StreamHandlerPoolInterface $exportTypeHandlerPool,
        protected SendErrorEmailServiceInterface $sendErrorEmailService,
        protected LoggerInterface $logger
    ) {
    }

    public function exportAll(AutomatedExportInterface $automatedExport): void
    {
        $customReportIds = $automatedExport->getCustomreportIds();
        foreach ($customReportIds as $customReportId) {
            try {
                $customReport = $this->customReportRepository->getById($customReportId);
                $handlers = $this->exportTypeHandlerPool->getHandlerInstances($automatedExport);
                foreach ($handlers as $handler) {
                    $handler->setAutomatedExport($automatedExport)
                        ->setCustomReport($customReport)
                        ->setHandlers($handlers);
                    $handler->startExport();
                    $handler->exportHeaders();
                }

                $reportCollection = $this->customReportManagement->getGenericReportCollection($customReport);
                foreach ($reportCollection as $reportRow) {
                    foreach ($handlers as $handler) {
                        $handler->exportChunk($reportRow->getData());
                    }
                }

                foreach ($handlers as $handler) {
                    $handler->exportFooters();
                    $handler->finalizeExport();
                }
            } catch (Exception $exception) {
                $this->logger->critical($exception);
                if ($errorEmailAddresses = $automatedExport->getErrorEmails()) {
                    $this->sendErrorEmailService->execute($errorEmailAddresses, [
                        'automated_export' => $automatedExport->getData(),
                        'custom_report_id' => $customReportId,
                        'exception' => $exception->__toString(),
                    ]);
                }
            }
        }
    }
}
