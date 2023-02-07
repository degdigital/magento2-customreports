<?php declare(strict_types=1);

namespace DEG\CustomReports\Api\Data;

use DEG\CustomReports\Model\CustomReport;

interface CustomReportInterface
{
    public function getId();

    public function getReportName(): string;

    public function getReportSql(): string;

    public function setId($id);

    public function setReportName(string $reportName): CustomReport;

    public function setReportSql(string $reportSql): CustomReport;
}
