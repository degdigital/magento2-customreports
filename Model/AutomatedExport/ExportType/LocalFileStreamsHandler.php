<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\AutomatedExportManagementInterface;
use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStreamFactory;
use DEG\CustomReports\Model\Config\Source\FileTypes;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\DataObject;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;

/**
 * @method AutomatedExportInterface getAutomatedExport()
 * @method CustomReportInterface    getCustomReport()
 * @method LocalFileStreamsHandler setAutomatedExport(AutomatedExportInterface $automatedExport)
 * @method LocalFileStreamsHandler setCustomReport(CustomReportInterface $customReport)
 */
class LocalFileStreamsHandler extends DataObject implements StreamHandlerInterface
{
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected WriteInterface $directory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var \DEG\CustomReports\Api\CustomReportManagementInterface
     */
    protected CustomReportManagementInterface $customReportManagement;

    /**
     * @var \DEG\CustomReports\Api\AutomatedExportManagementInterface
     */
    protected AutomatedExportManagementInterface $automatedExportManagement;

    /**
     * @var \DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStream[]
     */
    protected array $exportStreams = [];

    /**
     * @var \DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStreamFactory
     */
    protected LocalFileStreamFactory $localFileStreamFactory;

    /**
     * @param \Magento\Framework\Filesystem                             $filesystem
     * @param \DEG\CustomReports\Api\CustomReportManagementInterface    $customReportManagement
     * @param \DEG\CustomReports\Api\AutomatedExportManagementInterface $automatedExportManagement
     * @param LocalFileStreamFactory                                    $LocalFileStreamFactory
     * @param array                                                     $data
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Filesystem $filesystem,
        CustomReportManagementInterface $customReportManagement,
        AutomatedExportManagementInterface $automatedExportManagement,
        LocalFileStreamFactory $LocalFileStreamFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->filesystem = $filesystem;
        $this->directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->customReportManagement = $customReportManagement;
        $this->automatedExportManagement = $automatedExportManagement;
        $this->localFileStreamFactory = $LocalFileStreamFactory;
    }

    /**
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function startExport(): array
    {
        $automatedExport = $this->getAutomatedExport();
        $customReport = $this->getCustomReport();
        $directoryName = $automatedExport->getExportLocation();
        $this->directory->create($directoryName);
        $fileStem = $this->automatedExportManagement->getReplacedFilename($automatedExport, $customReport);
        $this->exportStreams = [];
        foreach ($automatedExport->getFileTypes() as $fileType) {
            $filename = $fileStem.'.'.$fileType;
            $stream = $this->directory->openFile($directoryName.'/'.$filename, 'w+');
            $this->exportStreams[] = $this->localFileStreamFactory->create()
                ->setFilename($filename)
                ->setFileType($fileType)
                ->setStream($stream);
        }

        return $this->exportStreams;
    }

    /**
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function exportHeaders()
    {
        $customReport = $this->getCustomReport();
        if ($customReport && ($columnList = $this->customReportManagement->getColumnsList($customReport))) {
            $this->exportChunk($columnList);
        }
    }

    /**
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function exportChunk(array $dataToWrite)
    {
        foreach ($this->exportStreams as $exportStream) {
            if ($exportStream->getFileType() == FileTypes::EXTENSION_CSV) {
                $exportStream->getStream()->writeCsv($dataToWrite);
                $exportStream->getStream()->lock();
            } //@todo: add support for other file types
        }
    }

    /**
     * @return void
     */
    public function finalizeExport()
    {
        foreach ($this->exportStreams as $exportStream) {
            $exportStream->getStream()->unlock();
            $exportStream->getStream()->close();
        }
    }
}
