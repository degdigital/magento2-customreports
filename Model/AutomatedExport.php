<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * @method int getId()
 * @method string getTitle()
 * @method string getCronExpr()
 * @method string|array getExportTypes()
 * @method string|array getFileTypes()
 * @method string getFilenamePattern()
 * @method string getExportLocation()
 * @method string getCreatedAt()
 * @method string getUpdatedAt()
 * @method string|array getCustomreportIds()
 * @method AutomatedExport setId(int $id)
 * @method AutomatedExport setTitle(string $title)
 * @method AutomatedExport setCronExpr(string|array $cronExpr)
 * @method AutomatedExport setExportTypes(string|array $exportTypes)
 * @method AutomatedExport setFileTypes(string|array $fileTypes)
 * @method AutomatedExport setFilenamePattern(string $filenamePattern)
 * @method AutomatedExport setExportLocation(string $exportLocation)
 * @method AutomatedExport setCreatedAt(string $createdAt)
 * @method AutomatedExport setUpdatedAt(string $updatedAt)
 * @method AutomatedExport setCustomreportIds(string|array $customreportIds)
 */
class AutomatedExport extends AbstractModel implements AutomatedExportInterface, IdentityInterface
{
    const CACHE_TAG = 'deg_customreports_automatedexport';

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\AutomatedExport::class);
    }
}
