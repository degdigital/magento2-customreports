<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\Config\Source\ExportTypes;
use Magento\Framework\DataObject;
use Magento\Framework\Filesystem\Io\SftpFactory;

/**
 * @method AutomatedExportInterface getAutomatedExport()
 * @method CustomReportInterface    getCustomReport()
 * @method LocalFileStreamsHandler setAutomatedExport(AutomatedExportInterface $automatedExport)
 * @method LocalFileStreamsHandler setCustomReport(CustomReportInterface $customReport)
 */
class RemoteFileStreamsHandler extends DataObject implements StreamHandlerInterface
{
    /**
     * @var \DEG\CustomReports\Model\AutomatedExport\ExportType\LocalFileStreamsHandler
     */
    protected LocalFileStreamsHandler $localFileStreamsHandler;

    /**
     * @var \Magento\Framework\Filesystem\Io\SftpFactory
     */
    protected SftpFactory $sftpFactory;

    /**
     * @var \DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStream[]
     */
    protected array $exportStreams = [];

    /**
     * @var bool
     */
    protected bool $isLocalFileAlreadyBeingGenerated;

    /**
     * @param \DEG\CustomReports\Model\AutomatedExport\ExportType\LocalFileStreamsHandler $localFileStreamsHandler
     * @param \Magento\Framework\Filesystem\Io\SftpFactory                                $sftpFactory
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
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function startExport()
    {
        $this->exportStreams = $this->localFileStreamsHandler->setAutomatedExport($this->getAutomatedExport())
            ->setCustomReport($this->getCustomReport())
            ->startExport();
    }

    /**
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function exportHeaders()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->exportHeaders();
        }
    }

    /**
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function exportChunk(array $dataToWrite)
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->exportChunk($dataToWrite);
        }
    }

    /**
     * @throws \Exception
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
            foreach ($this->getAutomatedExport()->getExportTypes() as $exportType) {
                if ($exportType == ExportTypes::LOCAL_FILE_DROP) {
                    $this->isLocalFileAlreadyBeingGenerated = true;
                }
            }
        }

        return $this->isLocalFileAlreadyBeingGenerated;
    }

    /**
     * @throws \Exception
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
}
