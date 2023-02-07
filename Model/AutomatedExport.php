<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class AutomatedExport extends AbstractModel implements AutomatedExportInterface, IdentityInterface
{
    const CACHE_TAG = 'deg_customreports_automatedexport';

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\AutomatedExport::class);
    }

    public function getTitle(): string
    {
        return $this->getData('title');
    }

    public function getCronExpr(): string
    {
        return $this->getData('cron_expr');
    }

    public function getExportTypes(): string|array
    {
        return $this->getData('export_types');
    }

    public function getFileTypes(): string|array
    {
        return $this->getData('file_types');
    }

    public function getFilenamePattern(): string
    {
        return $this->getData('filename_pattern');
    }

    public function getErrorEmails(): ?string
    {
        return $this->getData('error_emails');
    }

    public function getExportLocation(): string
    {
        return $this->getData('export_location');
    }

    public function getRemoteHost(): ?string
    {
        return $this->getData('remote_host');
    }

    public function getRemotePort(): ?string
    {
        return $this->getData('remote_port');
    }

    public function getRemoteUsername(): ?string
    {
        return $this->getData('remote_username');
    }

    public function getRemotePassword(): ?string
    {
        return $this->getData('remote_password');
    }

    public function getRemoteLocation(): ?string
    {
        return $this->getData('remote_location');
    }

    public function getEmailTemplate(): ?string
    {
        return $this->getData('email_template');
    }

    public function getEmailRecipients(): ?string
    {
        return $this->getData('email_recipients');
    }

    public function getCreatedAt(): ?string
    {
        return $this->getData('created_at');
    }

    public function getUpdatedAt(): ?string
    {
        return $this->getData('updated_at');
    }

    public function getCustomreportIds(): string|array
    {
        return $this->getData('customreport_ids');
    }

    public function setTitle(string $title): AutomatedExportInterface
    {
        return $this->setData('title', $title);
    }

    public function setCronExpr(string $cronExpr): AutomatedExportInterface
    {
        return $this->setData('cron_expr', $cronExpr);
    }

    public function setExportTypes(array|string $exportTypes): AutomatedExportInterface
    {
        return $this->setData('export_types', $exportTypes);
    }

    public function setFileTypes(array|string $fileTypes): AutomatedExportInterface
    {
        return $this->setData('file_types', $fileTypes);
    }

    public function setFilenamePattern(string $filenamePattern): AutomatedExportInterface
    {
        return $this->setData('filename_pattern', $filenamePattern);
    }

    public function setExportLocation(string $exportLocation): AutomatedExportInterface
    {
        return $this->setData('export_location', $exportLocation);
    }

    public function setErrorEmails(string $errorEmails): AutomatedExportInterface
    {
        return $this->setData('error_emails', $errorEmails);
    }

    public function setRemoteHost(string $remoteHost): AutomatedExportInterface
    {
        return $this->setData('remote_host', $remoteHost);
    }

    public function setRemotePort(string $remotePort): AutomatedExportInterface
    {
        return $this->setData('remote_port', $remotePort);
    }

    public function setRemoteUsername(string $remoteUsername): AutomatedExportInterface
    {
        return $this->setData('remote_username', $remoteUsername);
    }

    public function setRemotePassword(string $remotePassword): AutomatedExportInterface
    {
        return $this->setData('remote_password', $remotePassword);
    }

    public function setRemoteLocation(string $remoteLocation): AutomatedExportInterface
    {
        return $this->setData('remote_location');
    }

    public function setEmailTemplate(string $emailTemplate): AutomatedExportInterface
    {
        return $this->setData('email_template', $emailTemplate);
    }

    public function setEmailRecipients(string $emailRecipients): AutomatedExportInterface
    {
        return $this->setData('email_recipient', $emailRecipients);
    }

    public function setCreatedAt(string $createdAt): AutomatedExportInterface
    {
        return $this->setData('created_at', $createdAt);
    }

    public function setUpdatedAt(string $updatedAt): AutomatedExportInterface
    {
        return $this->setData('updated_at', $updatedAt);
    }

    public function setCustomreportIds(array|string $customreportIds): AutomatedExportInterface
    {
        return $this->setData('customreport_ids', $customreportIds);
    }

}
