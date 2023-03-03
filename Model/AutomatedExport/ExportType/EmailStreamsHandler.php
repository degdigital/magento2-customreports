<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\ConfigProviderInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\AutomatedExport\ExportType\Stream\LocalFileStream;
use DEG\CustomReports\Model\Mail\Template\TransportBuilder;
use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use DEG\CustomReports\Model\Mail\Template\TransportBuilderFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Responsible for exporting a 'stream' (of query results) to a file, attaching it to an email, and sending the email.
 * Whether the 'Local File Drop' export type is selected or not, this exporter must create a local file to upload. If
 * the 'Local File Drop' export type IS selected, this exporter does NOT duplicate a local file. It will use the file
 * created by the other exporter to prevent duplicate work.
 *
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

    protected ?TransportBuilder $combinedTransportBuilder = null;

    protected array $combinedTemplateVars = [];

    public function __construct(
        protected LocalFileStreamsHandler $localFileStreamsHandler,
        protected TransportBuilderFactory $transportBuilderFactory,
        protected ConfigProviderInterface $configProvider,
        protected TimezoneInterface $timezone,
        array $data = []
    ) {
        parent::__construct($data);
    }

    public function startExport()
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->startExport();
        }

        if ($this->isCombinedEmail()) {
            $this->combinedTemplateVars = $this->getDefaultTemplateVars($this->getAutomatedExport());
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

        if ($this->isCombinedEmail()) {
            $this->populateCombinedEmail();
        } else {
            $this->sendSingleReportEmail();
        }
    }

    /**
     * @throws MailException
     * @throws LocalizedException
     */
    public function finalizeExport(): void
    {
        if (!$this->isLocalFileAlreadyBeingExported()) {
            $this->localFileStreamsHandler->finalizeExport();
        }

        if ($this->isCombinedEmail()) {
            $this->sendCombinedEmail();
        }
    }

    protected function isCombinedEmail(): ?bool
    {
        return $this->getAutomatedExport()->getIsCombinedEmail();
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
    protected function sendSingleReportEmail(): void
    {
        if (!$this->isValid()) {
            return;
        }
        $automatedExport = $this->getAutomatedExport();
        $emailRecipients = $automatedExport->getEmailRecipients();

        foreach ($this->exportStreams as $exportStream) {
            $transportBuilder = $this->transportBuilderFactory->create();
            $templateVars = $this->getDefaultTemplateVars($automatedExport);
            $templateVars['custom_report'] = $this->getCustomReport();
            $templateVars['export_stream'] = $exportStream;
            $this->populateTransport($emailRecipients, $transportBuilder, $automatedExport, $templateVars);
            $transportBuilder->addAttachment(
                $exportStream->getStream()->readAll(),
                basename($exportStream->getFilename())
            );
            $transportBuilder->getTransport()->sendMessage();
        }
    }

    protected function populateCombinedEmail()
    {
        if (!$this->isValid()) {
            return;
        }

        //backwards compatibility: prevent errors with existing email templates that reference one report
        $this->combinedTemplateVars['custom_report'] = $this->getCustomReport();

        $this->combinedTemplateVars['custom_reports'][] = $this->getCustomReport();

        foreach ($this->exportStreams as $exportStream) {
            //backwards compatibility: prevent errors with existing email templates that reference one stream
            $this->combinedTemplateVars['export_stream'] = $exportStream;

            $this->combinedTemplateVars['export_streams'][] = $exportStream;
            $this->getTransportBuilder()->addAttachment(
                $exportStream->getStream()->readAll(),
                basename($exportStream->getFilename())
            );
        }
    }

    protected function getTransportBuilder(): TransportBuilder
    {
        if ($this->isCombinedEmail()) {
            if (!$this->combinedTransportBuilder) {
                $this->combinedTransportBuilder = $this->transportBuilderFactory->create();
            }

            return $this->combinedTransportBuilder;
        } else {
            return $this->transportBuilderFactory->create();
        }
    }

    /**
     * @throws MailException
     * @throws LocalizedException
     */
    protected function sendCombinedEmail()
    {
        $automatedExport = $this->getAutomatedExport();
        $emailRecipients = $automatedExport->getEmailRecipients();
        $transportBuilder = $this->getTransportBuilder();
        $this->populateTransport($emailRecipients, $transportBuilder, $automatedExport, $this->combinedTemplateVars);
        $transportBuilder->getTransport()->sendMessage();
    }

    /**
     * @param string $emailRecipients
     * @param TransportBuilder $transportBuilder
     * @param AutomatedExportInterface $automatedExport
     * @param array $templateVars
     * @return void
     * @throws MailException
     */
    protected function populateTransport(
        string $emailRecipients,
        TransportBuilder $transportBuilder,
        AutomatedExportInterface $automatedExport,
        array $templateVars
    ): void {
        foreach (explode(',', $emailRecipients) as $emailRecipient) {
            $transportBuilder->addTo($emailRecipient);
        }

        $senderParts = [];
        if ($sender = $automatedExport->getEmailSender()) {
            [$senderParts['email'], $senderParts['name']] = explode(':', $sender);
        }

        $transportBuilder->setFromByScope(!empty($senderParts) ? $senderParts
            : $this->configProvider->getEmailFrom());

        $emailTemplate = $automatedExport->getEmailTemplate();

        $transportBuilder->setTemplateIdentifier($emailTemplate)
            ->setTemplateOptions(['area' => Area::AREA_ADMINHTML, 'store' => 0])
            ->setTemplateVars($templateVars);
    }

    /**
     * @param AutomatedExportInterface $automatedExport
     * @return array
     */
    protected function getDefaultTemplateVars(AutomatedExportInterface $automatedExport): array
    {
        return [
            'automated_export' => $automatedExport,
            'd' => $this->timezone->date()->format('d'),
            'j' => $this->timezone->date()->format('j'),
            'm' => $this->timezone->date()->format('m'),
            'n' => $this->timezone->date()->format('n'),
            'Y' => $this->timezone->date()->format('Y'),
            'H' => $this->timezone->date()->format('H'),
            'i' => $this->timezone->date()->format('i'),
            's' => $this->timezone->date()->format('s'),
            'c' => $this->timezone->date()->format('c'),
        ];
    }

    /**
     * @return bool
     */
    protected function isValid(): bool
    {
        $automatedExport = $this->getAutomatedExport();
        $emailRecipients = $automatedExport->getEmailRecipients();
        if (!$emailRecipients) {
            return false;
        }

        return true;
    }
}
