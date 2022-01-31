<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;

interface AutomatedExportManagementInterface
{
    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface    $customReport
     *
     * @return string
     */
    public function getReplacedFilename(
        AutomatedExportInterface $automatedExport,
        CustomReportInterface $customReport
    ): string;
}
