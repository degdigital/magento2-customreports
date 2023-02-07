<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStream;
use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Io\SftpFactory;

/**
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
     * @param array                                                                       $data
     */
    public function __construct(
        LocalFileStreamsHandler $localFileStreamsHandler,
        SftpFactory $sftpFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->sftpFactory = $sftpFactory;
        $this->localFileStreamsHandler = $localFileStreamsHandler;
    }

    /**
     * @throws FileSystemException
     */
    public function startExport()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->exportStreams = $this->localFileStreamsHandler->setAutomatedExport($this->getAutomatedExport())
                ->setCustomReport($this->getCustomReport())
                ->startExport();
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
    public function exportHeaders()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->exportHeaders();
        }
    }

    /**
     * @throws FileSystemException
     */
    public function exportChunk(array $dataToWrite)
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->exportChunk($dataToWrite);
        }
    }

    /**
     * @throws Exception
     */
    public function finalizeExport()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->finalizeExport();
        }
        $this->uploadFiles();
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
        $sftp = $this->sftpFactory->create();
        $automatedExport = $this->getAutomatedExport();
        $host = $automatedExport->getRemoteHost();
        if ($remotePort = $automatedExport->getRemotePort()) {
            $host = $host . ':' . $remotePort;
        }
        $sftp->open([
            'host' => $host,
            'username' => $automatedExport->getRemoteUsername(),
            'password' => $automatedExport->getRemotePassword(),
        ]);

        foreach ($this->exportStreams as $exportStream) {
            // phpcs:disable Magento2.Functions.DiscouragedFunction
            $filePath = $automatedExport->getRemoteLocation().'/'.basename($exportStream->getFilename());
            $sftp->write($filePath, $exportStream->getFilepath());
        }
    }

    public function getExportStreams(): array
    {
        return $this->exportStreams;
    }
}
