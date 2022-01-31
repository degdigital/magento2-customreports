<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * @method int getId()
 * @method string getReportName()
 * @method string getReportSql()
 * @method CustomReport setId(int $id)
 * @method CustomReport setReportName(string $reportName)
 * @method CustomReport setReportSql(string $reportSql)
 */
class CustomReport extends AbstractModel implements CustomReportInterface, IdentityInterface
{
    const CACHE_TAG = 'deg_customreports_customreport';

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\CustomReport::class);
    }
}
