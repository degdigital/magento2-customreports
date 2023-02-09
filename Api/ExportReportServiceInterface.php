<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;

/**
 * Provides logic for executing reports associated to an automated export.
 */
interface ExportReportServiceInterface
{
    /**
     * Export all reports associated to an automated export.
     *
     * @param AutomatedExportInterface $automatedExport
     *
     * @return void
     */
    public function exportAll(AutomatedExportInterface $automatedExport): void;
}
