<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Api\CustomReportManagementInterface;
use Magento\Setup\Exception;
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
        $rawSql = $customReport->getReportSql();
        $formattedSql = $this->formatSql($rawSql);
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

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     *
     * @return array
     */
    public function getColumnTypes(CustomReportInterface $customReport): array
    {
        $rawSql = $customReport->getReportSql();
        $commentMatches = [];
        preg_match_all('~/\*(.*?)\*/~s',$rawSql, $commentMatches);
        $commentMatched = array_pop($commentMatches);
        $commentsCleaned = array_map('trim', $commentMatched);
        $result = [];
        foreach($commentsCleaned as $comment) {
            try {
                $columnFilterTypes = explode(',', $comment);
                foreach ($columnFilterTypes as $type) {
                    [$column, $typeFilter] = explode(':', $type);
                    $result[trim($column)] = trim($typeFilter);
                }
            } catch (\Exception $e) {

            }
        }
        return $result;
    }

}
