<?php declare(strict_types=1);
namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;

/**
 * @api
 */
interface StreamHandlerPoolInterface
{
    /**
     * Retrieve handler config
     *
     * @return array[]
     */
    public function getHandlerConfig(): array;

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     *
     * @return \DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerInterface[]
     */
    public function getHandlerInstances(AutomatedExportInterface $automatedExport): array;
}
