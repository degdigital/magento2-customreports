<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

interface DeleteDynamicCronInterface
{
    public function execute(string $automatedExportModelName);
}
