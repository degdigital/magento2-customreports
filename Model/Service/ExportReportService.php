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

    /**
     * For the given automated export, export all custom reports, for all configured export types, for all file types.
     * Performance note: this method specifically works in its current way to optimize performance. For a given custom
     * report, its query must only be run once, even if there are many export types and file types configured. This is
     * mission-critical as a single query might take minutes on its own, so all necessary export operations must be done
     * on that single query, despite the code being less intuitive/harder to follow.
     *
     * @param AutomatedExportInterface $automatedExport
     * @return void
     */
    public function exportAll(AutomatedExportInterface $automatedExport): void
    {
        $customReportIds = $automatedExport->getCustomreportIds();
        $handlers = $this->exportTypeHandlerPool->getHandlerInstances($automatedExport);

        try {
            foreach ($handlers as $handler) {
                $handler->setAutomatedExport($automatedExport)->setHandlers($handlers)->startExport();
            }
        } catch (Exception $exception) {
            $this->logger->critical($exception);
            if ($errorEmailAddresses = $automatedExport->getErrorEmails()) {
                $this->sendErrorEmailService->execute($errorEmailAddresses, [
                    'automated_export' => $automatedExport->getData(),
                    'exception' => $exception->__toString(),
                ]);
            }
        }

        foreach ($customReportIds as $customReportId) {
            try {
                $customReport = $this->customReportRepository->getById($customReportId);
                foreach ($handlers as $handler) {
                    $handler->setCustomReport($customReport);
                    $handler->startReportExport();
                    $handler->exportReportHeaders();
                }

                $reportCollection = $this->customReportManagement->getGenericReportCollection($customReport);
                foreach ($reportCollection as $reportRow) {
                    foreach ($handlers as $handler) {
                        $handler->exportReportChunk($reportRow->getData());
                    }
                }

                foreach ($handlers as $handler) {
                    $handler->exportReportFooters();
                    $handler->finalizeReportExport();
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

        try {
            foreach ($handlers as $handler) {
                $handler->finalizeExport();
            }
        } catch (Exception $exception) {
            $this->logger->critical($exception);
            if ($errorEmailAddresses = $automatedExport->getErrorEmails()) {
                $this->sendErrorEmailService->execute($errorEmailAddresses, [
                    'automated_export' => $automatedExport->getData(),
                    'exception' => $exception->__toString(),
                ]);
            }
        }
    }
}
