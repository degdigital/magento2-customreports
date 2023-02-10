<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use Exception;
use Magento\Framework\Api\ExtensionAttribute\JoinDataInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;
use Psr\Log\LoggerInterface as Logger;

class GenericReportCollection extends AbstractDb
{
    /**
     * A non-zero value to use when the size cannot be queried for performance reasons but size checking still needs
     * to succeed, e.g. for rendering purposes.
     */
    public const FAKE_SIZE = 1;

    /**
     * A large value to use when the total count cannot be queried for performance reasons but pagination is still
     * needed.
     */
    public const FAKE_LAST_PAGE = 1e9;

    protected CustomReportInterface $customReport;

    /**
     * GenericReportCollection constructor.
     *
     * @param EntityFactoryInterface $entityFactory
     * @param Logger $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ResourceConnection|null $resourceConnection
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        Logger $logger,
        FetchStrategyInterface $fetchStrategy,
        ResourceConnection $resourceConnection = null
    ) {
        $resourceConnection = $resourceConnection ?: ObjectManager::getInstance()->get(ResourceConnection::class);

        try {
            $connection = $resourceConnection->getConnectionByName('readonly');
        } catch (Exception) {
            $connection = $resourceConnection->getConnectionByName('default');
        }

        parent::__construct($entityFactory, $logger, $fetchStrategy, $connection);
    }

    public function setCustomReport(CustomReportInterface $customReport): static
    {
        $this->customReport = $customReport;

        return $this;
    }

    public function getCustomReport(): CustomReportInterface
    {
        return $this->customReport;
    }

    /**
     * Retrieves the collection size using the minimum amount of queries possible. If the number of items in the
     * collection is less than the page size, then the collection can simply be counted. If queries are allowed, then
     * the size is queried. If queries are not allowed, then a fake non-zero value is returned to preserve
     * functionality related to checking that the size is non-zero, e.g. during grid rendering.
     */
    public function getSize(): int
    {
        return $this->canCountTotals()
            ? $this->count()
            : ($this->getCustomReport()->getAllowCountQuery() ? parent::getSize() : static::FAKE_SIZE);
    }

    /**
     * Retrieves a last page number using the minimum amount of queries possible. If the number of items in the
     * collection is less than the page size, then there is only one page. If queries are allowed, then the last page
     * is queried. If queries are not allowed, then a fake value is returned to preserve pagination functionality.
     * Note that returning the fake page number can cause the collection to be unexpectedly empty if the collection/grid
     * is being filtered, the customer is on page 2+, and the filtered results only return enough results for one page.
     * In such a case, the user should check page one for results. This is preferable to executing a potentially slow
     * count(*) query.
     *
     * @return int
     */
    public function getLastPageNumber(): int
    {
        return $this->canCountTotals()
            ? 1
            : ($this->getCustomReport()->getAllowCountQuery()
                ? parent::getLastPageNumber()
                : (int) static::FAKE_LAST_PAGE);
    }

    /**
     * Returns true if it is possible to manually count the result size simply by looking at the number of items in
     * the collection, i.e. without getSize's additional count(*) query. If there are fewer items in the collection than
     * the page size, then there is clearly only one page and so the count can be inferred from the current collection
     * size without needing to perform a count(*) query. Also requires that either the current page is one, or the
     * collection has already been loaded. This is to prevent an infinite loop caused by a bad actor in
     * \Magento\Theme\Plugin\Data\Collection::afterGetCurPage, which is a plugin present on ALL collections that is
     * unfortunately triggering an (unnecessary in our case) count(*) query (from getLastPageNumber) on pages 2+.
     *
     * @return bool
     */
    public function canCountTotals(): bool
    {
        return ($this->_curPage == 1 || $this->isLoaded()) && $this->count() < $this->getPageSize();
    }

    /**
     * Intentionally left empty since this is a generic resource.
     * phpcs:disable Magento2.CodeAnalysis.EmptyBlock.DetectedFunction
     */
    public function getResource()
    {
    }

    /**
     * @param JoinDataInterface      $join
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     *
     * @return $this
     * @throws Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function joinExtensionAttribute(
        JoinDataInterface $join,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ): GenericReportCollection {
        throw new LocalizedException(__('joinExtensionAttribute is not allowed in GenericReportCollection'));
    }
}
