<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class CustomReport extends AbstractModel implements CustomReportInterface, IdentityInterface
{
    const CACHE_TAG = 'deg_customreports_customreport';

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\CustomReport::class);
    }

    public function getReportName(): string
    {
        return $this->getData('report_name');
    }

    public function getReportSql(): string
    {
        return $this->getData('report_sql');
    }

    public function setReportName(string $reportName): CustomReport
    {
        return $this->setData('report_name', $reportName);
    }

    public function setReportSql(string $reportSql): CustomReport
    {
        return $this->setData('report_sql', $reportSql);
    }
}
