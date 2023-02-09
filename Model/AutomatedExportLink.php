<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\AutomatedExportLinkInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class AutomatedExportLink extends AbstractModel implements AutomatedExportLinkInterface, IdentityInterface
{
    public const CACHE_TAG = 'deg_customreports_automatedexport_link';

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\AutomatedExportLink::class);
    }

    public function getCustomreportId()
    {
        return $this->getData(static::FIELD_CUSTOMREPORT_ID);
    }

    public function getAutomatedexportId(): int
    {
        return (int)$this->getData(static::FIELD_AUTOMATEDEXPORT_ID);
    }

    public function getCreatedAt(): ?string
    {
        return $this->getData(static::FIELD_CREATED_AT);
    }

    public function getUpdatedAt(): ?string
    {
        return $this->getData(static::FIELD_UPDATED_AT);
    }

    public function setCustomreportId(int $customreportId): AutomatedExportLink
    {
        return $this->setData(static::FIELD_CUSTOMREPORT_ID, $customreportId);
    }

    public function setAutomatedexportId(int $automatedexportId): AutomatedExportLink
    {
        return $this->setData(static::FIELD_AUTOMATEDEXPORT_ID, $automatedexportId);
    }

    public function setCreatedAt(string $createdAt): AutomatedExportLink
    {
        return $this->setData(static::FIELD_CREATED_AT, $createdAt);
    }

    public function setUpdatedAt(string $updatedAt): AutomatedExportLink
    {
        return $this->setData(static::FIELD_UPDATED_AT, $updatedAt);
    }
}
