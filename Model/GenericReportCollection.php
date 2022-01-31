<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinDataInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface as Logger;

class GenericReportCollection extends AbstractDb
{
    /**
     * GenericReportCollection constructor.
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface    $entityFactory
     * @param \Psr\Log\LoggerInterface                                     $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\App\ResourceConnection|null               $resourceConnection
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        Logger $logger,
        FetchStrategyInterface $fetchStrategy,
        ResourceConnection $resourceConnection = null
    ) {
        $resourceConnection = $resourceConnection ?: ObjectManager::getInstance()->get(ResourceConnection::class);

        /**
         * Previously, a custom 'readonly' connection was used here. This had to be removed to support Magento Cloud
         * projects. Magento Cloud support can add 'readonly' connections to the database, but cannot actually define
         * this connection in app/etc/env.php, and it cannot be defined manually as it will be removed on next deploy.
         */
        $connection = $resourceConnection->getConnectionByName('default');

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
     * @throws \Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function joinExtensionAttribute(
        JoinDataInterface $join,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ): GenericReportCollection {
        throw new LocalizedException(__('joinExtensionAttribute is not allowed in GenericReportCollection'));
    }
}
