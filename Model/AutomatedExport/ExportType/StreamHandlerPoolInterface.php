<?php declare(strict_types=1);
namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

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
     * @return array|\DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getHandlerInstances(): array;
}
