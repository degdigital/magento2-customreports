<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class AutomatedExport extends AbstractModel implements AutomatedExportInterface, IdentityInterface
{
    public const CACHE_TAG = 'deg_customreports_automatedexport';

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
        return $this->getData(static::FIELD_TITLE);
    }

    public function getCronExpr(): string
    {
        return $this->getData(static::FIELD_CRON_EXPR);
    }

    public function getExportTypes(): string|array
    {
        return $this->getData(static::FIELD_EXPORT_TYPES);
    }

    public function getFileTypes(): string|array
    {
        return $this->getData(static::FIELD_FILE_TYPES);
    }

    public function getFilenamePattern(): string
    {
        return $this->getData(static::FIELD_FILENAME_PATTERN);
    }

    public function getErrorEmails(): ?string
    {
        return $this->getData(static::FIELD_ERROR_EMAILS);
    }

    public function getExportLocation(): ?string
    {
        return $this->getData(static::FIELD_EXPORT_LOCATION);
    }

    public function getRemoteHost(): ?string
    {
        return $this->getData(static::FIELD_REMOTE_HOST);
    }

    public function getRemotePort(): ?string
    {
        return $this->getData(static::FIELD_REMOTE_PORT);
    }

    public function getRemoteUsername(): ?string
    {
        return $this->getData(static::FIELD_REMOTE_USERNAME);
    }

    public function getRemotePassword(): ?string
    {
        return $this->getData(static::FIELD_REMOTE_PASSWORD);
    }

    public function getRemoteLocation(): ?string
    {
        return $this->getData(static::FIELD_REMOTE_LOCATION);
    }

    public function getEmailTemplate(): ?string
    {
        return $this->getData(static::FIELD_EMAIL_TEMPLATE);
    }

    public function getEmailRecipients(): ?string
    {
        return $this->getData(static::FIELD_EMAIL_RECIPIENTS);
    }

    public function getCreatedAt(): ?string
    {
        return $this->getData(static::FIELD_CREATED_AT);
    }

    public function getUpdatedAt(): ?string
    {
        return $this->getData(static::FIELD_UPDATED_AT);
    }

    public function getCustomreportIds(): string|array
    {
        return $this->getData(static::FIELD_CUSTOMREPORT_IDS);
    }

    public function setTitle(string $title): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_TITLE, $title);
    }

    public function setCronExpr(string $cronExpr): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_CRON_EXPR, $cronExpr);
    }

    public function setExportTypes(array|string $exportTypes): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_EXPORT_TYPES, $exportTypes);
    }

    public function setFileTypes(array|string $fileTypes): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_FILE_TYPES, $fileTypes);
    }

    public function setFilenamePattern(string $filenamePattern): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_FILENAME_PATTERN, $filenamePattern);
    }

    public function setExportLocation(string $exportLocation): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_EXPORT_LOCATION, $exportLocation);
    }

    public function setErrorEmails(string $errorEmails): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_ERROR_EMAILS, $errorEmails);
    }

    public function setRemoteHost(string $remoteHost): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_REMOTE_HOST, $remoteHost);
    }

    public function setRemotePort(string $remotePort): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_REMOTE_PORT, $remotePort);
    }

    public function setRemoteUsername(string $remoteUsername): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_REMOTE_USERNAME, $remoteUsername);
    }

    public function setRemotePassword(string $remotePassword): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_REMOTE_PASSWORD, $remotePassword);
    }

    public function setRemoteLocation(string $remoteLocation): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_REMOTE_LOCATION);
    }

    public function setEmailTemplate(string $emailTemplate): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_EMAIL_TEMPLATE, $emailTemplate);
    }

    public function setEmailRecipients(string $emailRecipients): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_EMAIL_RECIPIENTS, $emailRecipients);
    }

    public function setCreatedAt(string $createdAt): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_CREATED_AT, $createdAt);
    }

    public function setUpdatedAt(string $updatedAt): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_UPDATED_AT, $updatedAt);
    }

    public function setCustomreportIds(array|string $customreportIds): AutomatedExportInterface
    {
        return $this->setData(static::FIELD_CUSTOMREPORT_IDS, $customreportIds);
    }

}
