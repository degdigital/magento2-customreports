<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

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
