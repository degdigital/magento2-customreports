<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\AutomatedExportLinkInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * @method int getId()
 * @method int getCustomreportId()
 * @method int getAutomatedexportId()
 * @method string getCreatedAt()
 * @method string getUpdatedAt()
 * @method AutomatedExportLink setId(int $id)
 * @method AutomatedExportLink setCustomreportId(int $customreportId)
 * @method AutomatedExportLink setAutomatedexportId(int $automatedexportId)
 * @method AutomatedExportLink setCreatedAt(string $createdAt)
 * @method AutomatedExportLink setUpdatedAt(string $createdAt)
 */
class AutomatedExportLink extends AbstractModel implements AutomatedExportLinkInterface, IdentityInterface
{
    const CACHE_TAG = 'deg_customreports_automatedexport_link';

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\AutomatedExportLink::class);
    }
}
