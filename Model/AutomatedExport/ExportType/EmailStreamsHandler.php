<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\ConfigProviderInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStream;
use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use DEG\CustomReports\Model\Mail\Template\TransportBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * @method AutomatedExportInterface getAutomatedExport()
 * @method CustomReportInterface    getCustomReport()
 * @method StreamHandlerInterface[] getHandlers()
 * @method LocalFileStreamsHandler setAutomatedExport(AutomatedExportInterface $automatedExport)
 * @method LocalFileStreamsHandler setCustomReport(CustomReportInterface $customReport)
 * @method LocalFileStreamsHandler setHandlers(StreamHandlerInterface[] $handlers)
 */
class EmailStreamsHandler extends DataObject implements StreamHandlerInterface
{
    /**
     * @var LocalFileStream[]
     */
    protected array $exportStreams = [];

    protected bool $isLocalFileAlreadyBeingGenerated;

    public function __construct(
        protected LocalFileStreamsHandler $localFileStreamsHandler,
        protected TransportBuilder $transportBuilder,
        protected ConfigProviderInterface $configProvider,
        protected TimezoneInterface $timezone,
        array $data = []
    ) {
        parent::__construct($data);
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
     * @throws FileSystemException
     */
    public function exportFooters()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->exportFooters();
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
        $this->sendEmail();
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

    public function getExportStreams(): array
    {
        return $this->exportStreams;
    }

    /**
     * Sends one email per exported file. This was chosen instead of attaching multiple files to a single email to help
     * prevent reaching the attachment size limit if the report(s) were large.
     *
     * @return void
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendEmail(): void
    {
        $automatedExport = $this->getAutomatedExport();
        $emailRecipients = $automatedExport->getEmailRecipients();
        if (!$emailRecipients) {
            return;
        }

        foreach ($this->exportStreams as $exportStream) {
            $this->transportBuilder->addAttachment(
                $exportStream->getStream()->readAll(),
                basename($exportStream->getFilename())
            );
            foreach (explode(',', $emailRecipients) as $emailRecipient) {
                $this->transportBuilder->addTo($emailRecipient);
            }
            $this->transportBuilder->setFromByScope($this->configProvider->getEmailFrom());

            $emailTemplate = $automatedExport->getEmailTemplate();
            $this->transportBuilder->setTemplateIdentifier($emailTemplate)
                ->setTemplateOptions(['area' => Area::AREA_ADMINHTML, 'store' => 0])
                ->setTemplateVars([
                    'automated_export' => $automatedExport,
                    'custom_report' => $this->getCustomReport(),
                    'export_stream' => $exportStream,
                    'd' => $this->timezone->date()->format('d'),
                    'j' => $this->timezone->date()->format('j'),
                    'm' => $this->timezone->date()->format('m'),
                    'n' => $this->timezone->date()->format('n'),
                    'Y' => $this->timezone->date()->format('Y'),
                    'H' => $this->timezone->date()->format('H'),
                    'i' => $this->timezone->date()->format('i'),
                    's' => $this->timezone->date()->format('s'),
                    'c' => $this->timezone->date()->format('c'),
                ]);
            $this->transportBuilder->getTransport()->sendMessage();
        }
    }
}
