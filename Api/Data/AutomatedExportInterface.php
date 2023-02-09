<?php declare(strict_types=1);

namespace DEG\CustomReports\Api\Data;

interface AutomatedExportInterface
{
    public const FIELD_TITLE = 'title';
    public const FIELD_CRON_EXPR = 'cron_expr';
    public const FIELD_EXPORT_TYPES = 'export_types';
    public const FIELD_FILE_TYPES = 'file_types';
    public const FIELD_FILENAME_PATTERN = 'filename_pattern';
    public const FIELD_ERROR_EMAILS = 'error_emails';
    public const FIELD_EXPORT_LOCATION = 'export_location';
    public const FIELD_REMOTE_HOST = 'remote_host';
    public const FIELD_REMOTE_PORT = 'remote_port';
    public const FIELD_REMOTE_USERNAME = 'remote_username';
    public const FIELD_REMOTE_PASSWORD = 'remote_password';
    public const FIELD_REMOTE_LOCATION = 'remote_location';
    public const FIELD_EMAIL_TEMPLATE = 'email_template';
    public const FIELD_EMAIL_RECIPIENTS = 'email_recipients';
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';
    public const FIELD_CUSTOMREPORT_IDS = 'customreport_ids';

    public function getId();

    public function getTitle(): string;

    public function getCronExpr(): string;

    public function getExportTypes(): string|array;

    public function getFileTypes(): string|array;

    public function getFilenamePattern(): string;

    public function getEmailTemplate(): ?string;

    public function getEmailRecipients(): ?string;

    public function getErrorEmails(): ?string;

    public function getExportLocation(): ?string;

    public function getRemoteHost(): ?string;

    public function getRemotePort(): ?string;

    public function getRemoteUsername(): ?string;

    public function getRemotePassword(): ?string;

    public function getRemoteLocation(): ?string;

    public function getCreatedAt(): ?string;

    public function getUpdatedAt(): ?string;

    public function getCustomreportIds(): string|array;

    public function setId($id);

    public function setTitle(string $title): AutomatedExportInterface;

    public function setCronExpr(string $cronExpr): AutomatedExportInterface;

    public function setExportTypes(string|array $exportTypes): AutomatedExportInterface;

    public function setFileTypes(string|array $fileTypes): AutomatedExportInterface;

    public function setFilenamePattern(string $filenamePattern): AutomatedExportInterface;

    public function setExportLocation(string $exportLocation): AutomatedExportInterface;

    public function setErrorEmails(string $errorEmails): AutomatedExportInterface;

    public function setEmailTemplate(string $emailTemplate): AutomatedExportInterface;

    public function setEmailRecipients(string $emailRecipients): AutomatedExportInterface;

    public function setRemoteHost(string $remoteHost): AutomatedExportInterface;

    public function setRemotePort(string $remotePort): AutomatedExportInterface;

    public function setRemoteUsername(string $remoteUsername): AutomatedExportInterface;

    public function setRemotePassword(string $remotePassword): AutomatedExportInterface;

    public function setRemoteLocation(string $remoteLocation): AutomatedExportInterface;

    public function setCreatedAt(string $createdAt): AutomatedExportInterface;

    public function setUpdatedAt(string $updatedAt): AutomatedExportInterface;

    public function setCustomreportIds(string|array $customreportIds): AutomatedExportInterface;
}
