<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Api\CustomReportManagementInterface;
use Zend_Db_Expr;

class CustomReportManagement implements CustomReportManagementInterface
{
    /**
     * @var GenericReportCollectionFactory
     */
    protected GenericReportCollectionFactory $genericReportCollectionFactory;

    /**
     * @param \DEG\CustomReports\Model\GenericReportCollectionFactory|null $genericReportCollectionFactory
     */
    public function __construct(
        GenericReportCollectionFactory $genericReportCollectionFactory
    ) {
        $this->genericReportCollectionFactory = $genericReportCollectionFactory;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     *
     * @return GenericReportCollection
     */
    public function getGenericReportCollection(CustomReportInterface $customReport): GenericReportCollection
    {
        $genericReportCollection = $this->genericReportCollectionFactory->create();
        $formattedSql = $this->formatSql($customReport->getReportSql());
        $genericReportCollection->getSelect()->from(new Zend_Db_Expr('('.$formattedSql.')'));

        return $genericReportCollection;
    }

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     *
     * @return string[]
     */
    public function getColumnsList(CustomReportInterface $customReport): array
    {
        $columnsCollection = $this->getGenericReportCollection($customReport);
        $columnsCollection->getSelect()->limitPage(1, 1);
        $firstItem = $columnsCollection->getFirstItem();

        return array_keys($firstItem->getData());
    }

    /**
     * @param string $rawSql
     *
     * @return string
     */
    protected function formatSql(string $rawSql): string
    {
        return trim($rawSql, ';');
    }
}
