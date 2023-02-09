<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Api\CustomReportManagementInterface;
use Zend_Db_Expr;

class CustomReportManagement implements CustomReportManagementInterface
{
    protected array $reportCollections = [];

    public function __construct(
        protected GenericReportCollectionFactory $genericReportCollectionFactory
    ) {
    }

    public function getGenericReportCollection(CustomReportInterface $customReport): GenericReportCollection
    {
        if (empty($this->reportCollections[$customReport->getId()])) {
            $genericReportCollection = $this->genericReportCollectionFactory->create();
            $formattedSql = $this->formatSql($customReport->getReportSql());
            $genericReportCollection->getSelect()->from(new Zend_Db_Expr('(' . $formattedSql . ')'));

            $this->reportCollections[$customReport->getId()] = $genericReportCollection;
        }

        return $this->reportCollections[$customReport->getId()];
    }

    public function getColumnsList(CustomReportInterface $customReport): array
    {
        $columnsCollection = $this->getGenericReportCollection($customReport);
        $firstItem = $columnsCollection->getFirstItem();

        return array_keys($firstItem->getData());
    }

    protected function formatSql(string $rawSql): string
    {
        return trim($rawSql, ';');
    }
}
