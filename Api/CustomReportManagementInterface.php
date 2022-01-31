<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\GenericReportCollection;

interface CustomReportManagementInterface
{
    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     *
     * @return GenericReportCollection
     */
    public function getGenericReportCollection(CustomReportInterface $customReport): GenericReportCollection;

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     *
     * @return string[]
     */
    public function getColumnsList(CustomReportInterface $customReport): array;
}
