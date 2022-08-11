<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Service;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Api\ExportReportServiceInterface;
use DEG\CustomReports\Api\SendErrorEmailServiceInterface;
use DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerPoolInterface;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Exception;
use Psr\Log\LoggerInterface;

class ExportReportService implements ExportReportServiceInterface
{
    /**
     * @var \DEG\CustomReports\Api\CustomReportRepositoryInterface
     */
    protected CustomReportRepositoryInterface $customReportRepository;

    /**
     * @var \DEG\CustomReports\Registry\CurrentCustomReport
     */
    protected CurrentCustomReport $currentCustomReportRegistry;

    /**
     * @var \DEG\CustomReports\Api\CustomReportManagementInterface
     */
    protected CustomReportManagementInterface $customReportManagement;

    /**
     * @var \DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerPoolInterface
     */
    protected StreamHandlerPoolInterface $exportTypeHandlerPool;

    /**
     * @var \DEG\CustomReports\Api\SendErrorEmailServiceInterface
     */
    protected SendErrorEmailServiceInterface $sendErrorEmailService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    public function __construct(
        CustomReportRepositoryInterface $customReportRepository,
        CustomReportManagementInterface $customReportManagement,
        StreamHandlerPoolInterface $exportTypeHandlerPool,
        SendErrorEmailServiceInterface $sendErrorEmailService,
        LoggerInterface $logger
    ) {
        $this->customReportRepository = $customReportRepository;
        $this->customReportManagement = $customReportManagement;
        $this->exportTypeHandlerPool = $exportTypeHandlerPool;
        $this->sendErrorEmailService = $sendErrorEmailService;
        $this->logger = $logger;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     *
     * @return void
     */
    public function exportAll(AutomatedExportInterface $automatedExport)
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
                $reportCollection->setPageSize(1000);
                $numPages = $reportCollection->getLastPageNumber();
                for ($currentPage = 1; $currentPage <= $numPages; $currentPage++) {
                    $reportCollection->setCurPage($currentPage);
                    foreach ($reportCollection as $reportRow) {
                        foreach ($handlers as $handler) {
                            $handler->exportChunk($reportRow->getData());
                        }
                    }
                }

                foreach ($handlers as $handler) {
                    $handler->finalizeExport();
                }
            } catch (Exception $exception) {
                $this->logger->critical($exception);
                if ($errorEmailAddresses = $automatedExport->getErrorEmails()) {
                    $this->sendErrorEmailService->execute($errorEmailAddresses, [
                        'automated_export' => $automatedExport->getData(),
                        'custom_report_id' => $customReportId,
                        'exception' => $exception,
                    ]);
                }
            }
        }
    }
}
