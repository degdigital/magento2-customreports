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

    public function getGenericReportCollection(CustomReportInterface $customReport, bool $forceReload = false): GenericReportCollection
    {
        if (empty($this->reportCollections[$customReport->getId()]) || $forceReload) {
            $genericReportCollection = $this->genericReportCollectionFactory->create();
            $genericReportCollection->setCustomReport($customReport);
            $formattedSql = $this->formatSql($customReport->getReportSql());

            // Some queries can be negatively impacted (performance-wise) by being wrapped in a "select * from (...)"
            // statement like this (i.e., the performance of getting a single page of results is equivalent to getting
            // all pages). But there is no known library to actually parse a query string and generate a Zend_Db_Select.
            $genericReportCollection->getSelect()->from(new Zend_Db_Expr('(' . $formattedSql . ')'));

            $this->reportCollections[$customReport->getId()] = $genericReportCollection;
        }

        return $this->reportCollections[$customReport->getId()];
    }

    public function getColumnsList(CustomReportInterface $customReport, bool $filtersPresent = false): array
    {
        $columnsCollection = $this->getGenericReportCollection($customReport, $filtersPresent);
        $firstItem = $columnsCollection->getFirstItem();

        return array_keys($firstItem->getData());
    }

    protected function formatSql(string $rawSql): string
    {
        return trim($rawSql, ';');
    }
}
