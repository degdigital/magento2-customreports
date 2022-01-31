<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;

interface CreateDynamicCronInterface
{
    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     *
     * @return void
     */
    public function execute(AutomatedExportInterface $automatedExport);
}
