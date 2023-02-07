<?php declare(strict_types=1);

namespace DEG\CustomReports\Api\Data;

interface AutomatedExportInterface
{
    public function getId();

    public function getTitle(): string;

    public function getCronExpr(): string;

    public function getExportTypes(): string|array;

    public function getFileTypes(): string|array;

    public function getFilenamePattern(): string;

    public function getEmailTemplate(): ?string;

    public function getEmailRecipients(): ?string;

    public function getErrorEmails(): ?string;

    public function getExportLocation(): string;

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
