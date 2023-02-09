<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\AutomatedExportManagementInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\Config\Source\FileTypes;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class AutomatedExportManagement implements AutomatedExportManagementInterface
{

    private Filesystem $filesystem;

    public function __construct(protected TimezoneInterface $timezone, Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     * @return string
     */
    public function getReplacedFilestem(
        AutomatedExportInterface $automatedExport,
        CustomReportInterface $customReport
    ): string {
        $formattedReportName = $customReport->getReportName();

        $replaceableVariables = [
            static::VARIABLE_D => $this->timezone->date()->format('d'),
            static::VARIABLE_M => $this->timezone->date()->format('m'),
            static::VARIABLE_Y => $this->timezone->date()->format('y'),
            static::VARIABLE_Y_LONG => $this->timezone->date()->format('Y'),
            static::VARIABLE_H => $this->timezone->date()->format('H'),
            static::VARIABLE_I => $this->timezone->date()->format('i'),
            static::VARIABLE_S => $this->timezone->date()->format('s'),
            static::VARIABLE_W => $this->timezone->date()->format('W'),
            static::VARIABLE_REPORTNAME => $formattedReportName,
        ];
        $filenamePattern = $automatedExport->getFilenamePattern();

        return str_replace(
            array_keys($replaceableVariables),
            array_values($replaceableVariables),
            $filenamePattern
        );
    }

    public function getFilename(
        AutomatedExportInterface $automatedExport,
        CustomReportInterface $customReport,
        string $fileType
    ): string {
        $fileStem = $this->getReplacedFilestem($automatedExport, $customReport);

        return $fileStem . '.' . $this->getFileExtension($fileType);
    }

    public function getFileExtension(string $fileType): string
    {
        $fileExtension = $fileType;
        if (str_contains($fileType, FileTypes::EXTENSION_METADATA_SEPARATOR)) {
            $fileExtension = strtok($fileType, FileTypes::EXTENSION_METADATA_SEPARATOR);
        }

        return $fileExtension;
    }

    public function getAbsoluteLocalFilepath(
        AutomatedExportInterface $automatedExport,
        CustomReportInterface $customReport,
        string $fileType
    ): string {
        $fileStem = $this->getReplacedFilestem($automatedExport, $customReport);
        $filename = $fileStem . '.' . $this->getFileExtension($fileType);
        $directory = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);

        return $directory->getAbsolutePath() . $automatedExport->getExportLocation() . '/' . $filename;
    }
}
