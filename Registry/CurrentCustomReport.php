<?php declare(strict_types=1);

namespace DEG\CustomReports\Registry;

use DEG\CustomReports\Api\Data\CustomReportInterface;

class CurrentCustomReport
{
    /**
     * @var \DEG\CustomReports\Api\Data\CustomReportInterface|null
     */
    private $currentCustomReport;

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface|null $currentCustomReport
     */
    public function set(CustomReportInterface $currentCustomReport): void
    {
        $this->currentCustomReport = $currentCustomReport;
    }

    /**
     * @return \DEG\CustomReports\Api\Data\CustomReportInterface|null
     */
    public function get(): ?CustomReportInterface
    {
        return $this->currentCustomReport;
    }
}
