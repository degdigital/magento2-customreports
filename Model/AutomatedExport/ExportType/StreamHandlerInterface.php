<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;

/**
 * @method AutomatedExportInterface getAutomatedExport()
 * @method CustomReportInterface    getCustomReport()
 * @method StreamHandlerInterface setAutomatedExport(AutomatedExportInterface $automatedExport)
 * @method StreamHandlerInterface setCustomReport(CustomReportInterface $customReport)
 */
interface StreamHandlerInterface
{
    public function startExport();

    public function exportHeaders();

    public function exportChunk(array $dataToWrite);

    public function finalizeExport();
}
