<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

/**
 * Provides support for removing a dynamic cron (an entry in the core_config_data table).
 */
interface DeleteDynamicCronInterface
{
    /**
     * @param string $automatedExportModelName
     *
     * @return void
     */
    public function execute(string $automatedExportModelName);
}
