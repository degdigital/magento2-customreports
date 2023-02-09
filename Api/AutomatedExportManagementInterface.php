<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;

interface AutomatedExportManagementInterface
{
    public const VARIABLE_REPORTNAME = '%reportname%';
    public const VARIABLE_D = '%d%';
    public const VARIABLE_M = '%m%';
    public const VARIABLE_Y = '%y%';
    public const VARIABLE_Y_LONG = '%Y%';
    public const VARIABLE_H = '%h%';
    public const VARIABLE_I = '%i%';
    public const VARIABLE_S = '%s%';
    public const VARIABLE_W = '%W%';

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     * @return string
     */
    public function getReplacedFilestem(
        AutomatedExportInterface $automatedExport,
        CustomReportInterface $customReport
    ): string;

    public function getFilename(
        AutomatedExportInterface $automatedExport,
        CustomReportInterface $customReport,
        string $fileType
    ): string;

    public function getAbsoluteLocalFilepath(
        AutomatedExportInterface $automatedExport,
        CustomReportInterface $customReport,
        string $fileType
    ): string;

    public function getFileExtension(string $fileType): string;
}
