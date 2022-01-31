<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\AutomatedExportManagementInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class AutomatedExportManagement implements AutomatedExportManagementInterface
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private TimezoneInterface $timezone;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     */
    public function __construct(TimezoneInterface $timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface    $customReport
     *
     * @return string
     */
    public function getReplacedFilename(
        AutomatedExportInterface $automatedExport,
        CustomReportInterface $customReport
    ): string {
        $formattedReportName = strtolower(str_replace(' ', '_', $customReport->getReportName()));

        $replaceableVariables = [
            '%d%' => $this->timezone->date()->format('d'),
            '%m%' => $this->timezone->date()->format('m'),
            '%y%' => $this->timezone->date()->format('y'),
            '%Y%' => $this->timezone->date()->format('Y'),
            '%h%' => $this->timezone->date()->format('H'),
            '%i%' => $this->timezone->date()->format('i'),
            '%s%' => $this->timezone->date()->format('s'),
            '%W%' => $this->timezone->date()->format('W'),
            '%reportname%' => $formattedReportName,
        ];
        $filenamePattern = $automatedExport->getFilenamePattern();

        return str_replace(
            array_keys($replaceableVariables),
            array_values($replaceableVariables),
            $filenamePattern
        );
    }
}
