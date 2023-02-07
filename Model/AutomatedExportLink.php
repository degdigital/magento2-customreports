<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\AutomatedExportLinkInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class AutomatedExportLink extends AbstractModel implements AutomatedExportLinkInterface, IdentityInterface
{
    const CACHE_TAG = 'deg_customreports_automatedexport_link';

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
        return $this->getData('customreport_id');
    }

    public function getAutomatedexportId(): int
    {
        return (int)$this->getData('automatedexport_id');
    }

    public function getCreatedAt(): ?string
    {
        return $this->getData('created_at');
    }

    public function getUpdatedAt(): ?string
    {
        return $this->getData('updated_at');
    }

    public function setCustomreportId(int $customreportId): AutomatedExportLink
    {
        return $this->setData('customreport_id', $customreportId);
    }

    public function setAutomatedexportId(int $automatedexportId): AutomatedExportLink
    {
        return $this->setData('automatedexport_id', $automatedexportId);
    }

    public function setCreatedAt(string $createdAt): AutomatedExportLink
    {
        return $this->setData('created_at', $createdAt);
    }

    public function setUpdatedAt(string $updatedAt): AutomatedExportLink
    {
        return $this->setData('updated_at', $updatedAt);
    }
}
