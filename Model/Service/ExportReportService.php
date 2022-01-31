<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Service;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Api\ExportReportServiceInterface;
use DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerPoolInterface;
use DEG\CustomReports\Registry\CurrentCustomReport;

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
    private CustomReportManagementInterface $customReportManagement;

    /**
     * @var \DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerPoolInterface
     */
    private StreamHandlerPoolInterface $exportTypeHandlerPool;

    public function __construct(
        CustomReportRepositoryInterface $customReportRepository,
        CustomReportManagementInterface $customReportManagement,
        StreamHandlerPoolInterface $exportTypeHandlerPool
    ) {
        $this->customReportRepository = $customReportRepository;
        $this->customReportManagement = $customReportManagement;
        $this->exportTypeHandlerPool = $exportTypeHandlerPool;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function exportAll(AutomatedExportInterface $automatedExport)
    {
        $customReportIds = $automatedExport->getCustomreportIds();
        foreach ($customReportIds as $customReportId) {
            $customReport = $this->customReportRepository->getById($customReportId);
            $handlers = $this->exportTypeHandlerPool->getHandlerInstances();
            foreach ($handlers as $handler) {
                $handler->setAutomatedExport($automatedExport)->setCustomReport($customReport);
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
        }
    }
}
