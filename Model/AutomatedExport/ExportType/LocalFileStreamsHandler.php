<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use ArrayIterator;
use DEG\CustomReports\Api\AutomatedExportManagementInterface;
use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\AutomatedExport\ExcelXmlConverter;
use DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStream;
use DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStreamFactory;
use DEG\CustomReports\Model\Config\Source\FileTypes;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Convert\Excel;
use Magento\Framework\Convert\ExcelFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;

/**
 * @method AutomatedExportInterface getAutomatedExport()
 * @method CustomReportInterface    getCustomReport()
 * @method StreamHandlerInterface[] getHandlers()
 * @method LocalFileStreamsHandler setAutomatedExport(AutomatedExportInterface $automatedExport)
 * @method LocalFileStreamsHandler setCustomReport(CustomReportInterface $customReport)
 * @method LocalFileStreamsHandler setHandlers(StreamHandlerInterface[] $handlers)
 */
class LocalFileStreamsHandler extends DataObject implements StreamHandlerInterface
{
    protected WriteInterface $directory;

    /**
     * @var LocalFileStream[]
     */
    protected array $exportStreams = [];

    /**
     * @throws FileSystemException
     */
    public function __construct(
        protected Filesystem $filesystem,
        protected CustomReportManagementInterface $customReportManagement,
        protected AutomatedExportManagementInterface $automatedExportManagement,
        protected LocalFileStreamFactory $localFileStreamFactory,
        protected ExcelXmlConverter $excelConverter,
        array $data = []
    ) {
        parent::__construct($data);
        $this->directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    }

    /**
     * @throws FileSystemException
     */
    public function startExport(): array
    {
        $automatedExport = $this->getAutomatedExport();
        $customReport = $this->getCustomReport();
        $directoryName = $automatedExport->getExportLocation();
        $this->directory->create($directoryName);
        $this->exportStreams = [];
        foreach ($automatedExport->getFileTypes() as $fileType) {
            $filename = $this->automatedExportManagement->getFilename($automatedExport, $customReport, $fileType);
            $localFilepath = $this->directory->getAbsolutePath() . $directoryName . '/' . $filename;
            $stream = $this->directory->openFile($localFilepath, 'w+');
            $stream->lock();
            $this->exportStreams[] = $this->localFileStreamFactory->create()
                ->setFilename($filename)
                ->setFilepath($localFilepath)
                ->setFileType($fileType)
                ->setStream($stream);
        }

        return $this->exportStreams;
    }

    /**
     * @throws FileSystemException
     */
    public function exportHeaders()
    {
        $customReport = $this->getCustomReport();
        if ($customReport && ($columnList = $this->customReportManagement->getColumnsList($customReport))) {
            $this->exportHeader($columnList);
        }
    }

    /**
     * @throws FileSystemException
     */
    private function exportHeader(array $columnList)
    {
        foreach ($this->exportStreams as $exportStream) {
            if ($exportStream->getFileType() == FileTypes::EXTENSION_XML_EXCEL) {
                $xmlData = $this->excelConverter->getXmlHeader();
                $exportStream->getStream()->write($xmlData);
            }
            $this->exportChunk($columnList);
        }
    }

    /**
     * @throws FileSystemException
     */
    public function exportFooters()
    {
        foreach ($this->exportStreams as $exportStream) {
            if ($exportStream->getFileType() == FileTypes::EXTENSION_XML_EXCEL) {
                $xmlData = $this->excelConverter->getXmlFooter();
                $exportStream->getStream()->write($xmlData);
            }
        }
    }

    /**
     * @throws FileSystemException
     */
    public function exportChunk(array $dataToWrite, string $rowType = null)
    {
        foreach ($this->exportStreams as $exportStream) {
            switch ($exportStream->getFileType()) {
                case FileTypes::EXTENSION_CSV:
                    $exportStream->getStream()->writeCsv($dataToWrite);
                    break;
                case FileTypes::EXTENSION_TSV:
                    $exportStream->getStream()->writeCsv($dataToWrite, "\t");
                    break;
                case FileTypes::EXTENSION_TXT_PIPE:
                    $exportStream->getStream()->writeCsv($dataToWrite, "|");
                    break;
                case FileTypes::EXTENSION_XML_EXCEL:
                    $xmlData = $this->excelConverter->getXmlRow($dataToWrite);
                    $exportStream->getStream()->write($xmlData);
                    break;
            }
        }
    }

    public function finalizeExport(): void
    {
        foreach ($this->exportStreams as $exportStream) {
            $exportStream->getStream()->unlock();
            $exportStream->getStream()->close();
        }
    }

    public function getExportStreams(): array
    {
        return $this->exportStreams;
    }
}
