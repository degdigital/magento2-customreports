<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;

/**
 * Provides support for removing a dynamic cron (an entry in the core_config_data table).
 */
interface DeleteDynamicCronInterface
{
    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     *
     * @return void
     */
    public function execute(AutomatedExportInterface $automatedExport);
}
