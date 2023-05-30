<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStream;
use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Io\SftpFactory;
use Magento\Framework\Filesystem\Io\FtpFactory;

/**
 * Responsible for exporting a 'stream' (of query results) to a file on the local file system and uploading it to the
 * configured (S)FTP server. Whether the 'Local File Drop' export type is selected or not, this exporter must create a
 * local file to upload. If the 'Local File Drop' export type IS selected, this exporter does NOT duplicate a local
 * file. It will use the file created by the other exporter to prevent duplicate work.
 *
 * @method AutomatedExportInterface getAutomatedExport()
 * @method CustomReportInterface    getCustomReport()
 * @method StreamHandlerInterface[] getHandlers()
 * @method LocalFileStreamsHandler setAutomatedExport(AutomatedExportInterface $automatedExport)
 * @method LocalFileStreamsHandler setCustomReport(CustomReportInterface $customReport)
 * @method LocalFileStreamsHandler setHandlers(StreamHandlerInterface[] $handlers)
 */
class RemoteFileStreamsHandler extends DataObject implements StreamHandlerInterface
{
    /**
     * @var LocalFileStreamsHandler
     */
    protected LocalFileStreamsHandler $localFileStreamsHandler;

    /**
     * @var SftpFactory
     */
    protected SftpFactory $sftpFactory;

    /**
     * @var FtpFactory
     */
    protected FtpFactory $ftpFactory;

    /**
     * @var LocalFileStream[]
     */
    protected array $exportStreams = [];

    /**
     * @var bool
     */
    protected bool $isLocalFileAlreadyBeingGenerated;

    /**
     * @param LocalFileStreamsHandler $localFileStreamsHandler
     * @param SftpFactory $sftpFactory
     * @param FtpFactory $ftpFactory
     * @param array $data
     */
    public function __construct(
        LocalFileStreamsHandler $localFileStreamsHandler,
        SftpFactory $sftpFactory,
        FtpFactory $ftpFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->sftpFactory = $sftpFactory;
        $this->ftpFactory = $ftpFactory;
        $this->localFileStreamsHandler = $localFileStreamsHandler;
    }

    public function startExport()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->startExport();
        }
    }

    /**
     * @throws FileSystemException
     */
    public function startReportExport()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->exportStreams = $this->localFileStreamsHandler->setAutomatedExport($this->getAutomatedExport())
                ->setCustomReport($this->getCustomReport())
                ->startReportExport();
        } else {
            foreach ($this->getHandlers() as $handler) {
                if ($handler instanceof LocalFileStreamsHandler) {
                    $this->exportStreams = $handler->getExportStreams();
                    break;
                }
            }
        }
    }

    /**
     * @throws FileSystemException
     */
    public function exportReportHeaders()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->exportReportHeaders();
        }
    }

    /**
     * @throws FileSystemException
     */
    public function exportReportChunk(array $dataToWrite)
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->exportReportChunk($dataToWrite);
        }
    }

    /**
     * @throws FileSystemException
     */
    public function exportReportFooters()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->exportReportFooters();
        }
    }

    /**
     * @throws Exception
     */
    public function finalizeReportExport()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->finalizeReportExport();
        }
        $this->uploadFiles();
    }

    public function finalizeExport(): void
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->finalizeExport();
        }
    }

    protected function isLocalFileAlreadyBeingExported(): bool
    {
        if (!isset($this->isLocalFileAlreadyBeingGenerated)) {
            $this->isLocalFileAlreadyBeingGenerated = false;
            foreach ($this->getHandlers() as $handler) {
                if ($handler instanceof LocalFileStreamsHandler) {
                    $this->isLocalFileAlreadyBeingGenerated = true;
                }
            }
        }

        return $this->isLocalFileAlreadyBeingGenerated;
    }

    /**
     * @throws Exception
     */
    protected function uploadFiles()
    {
        $automatedExport = $this->getAutomatedExport();
        $host = $automatedExport->getRemoteHost();
        if ($remotePort = $automatedExport->getRemotePort()) {
            $host = $host . ':' . $remotePort;
        }

        $connection = ($remotePort == '21' ? $this->ftpFactory->create() : $this->sftpFactory->create());
        $connection->open([
            'host' => $host,
            'username' => $automatedExport->getRemoteUsername(),
            'password' => $automatedExport->getRemotePassword(),
        ]);

        foreach ($this->exportStreams as $exportStream) {
            // phpcs:disable Magento2.Functions.DiscouragedFunction
            $filePath = $automatedExport->getRemoteLocation().'/'.basename($exportStream->getFilename());
            $connection->write($filePath, $exportStream->getFilepath());
        }
    }

    public function getExportStreams(): array
    {
        return $this->exportStreams;
    }
}
