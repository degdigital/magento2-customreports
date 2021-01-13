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
use Magento\Framework\DB\Adapter\AdapterInterface;
use Psr\Log\LoggerInterface as Logger;

class GenericReportCollection extends AbstractDb
{
    /**
     * GenericReportCollection constructor.
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface    $entityFactory
     * @param \Psr\Log\LoggerInterface                                     $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null          $connection
     * @param \Magento\Framework\App\ResourceConnection|null               $resourceConnection
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        Logger $logger,
        FetchStrategyInterface $fetchStrategy,
        AdapterInterface $connection = null,
        ResourceConnection $resourceConnection = null
    ) {
        $resourceConnection = $resourceConnection ?: ObjectManager::getInstance()->get(ResourceConnection::class);

        /**
         * @todo: Had to remove the connectionByName = 'readonly' temporarily until readonly connection
         * is added to Magento Cloud Pro project by Magento Cloud support
         */
        $connection = $resourceConnection->getConnectionByName('default');

        parent::__construct($entityFactory, $logger, $fetchStrategy, $connection);
    }

    /**
     * Intentionally left empty since this is a generic resource.
     *
     * @noinspection PhpMissingReturnTypeInspection*/
    public function getResource()
    {
    }

    /**
     * @param JoinDataInterface      $join
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     *
     * @return $this
     * @throws \Exception
     */
    public function joinExtensionAttribute(
        JoinDataInterface $join,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ): GenericReportCollection {
        throw new Exception('joinExtensionAttribute is not allowed in GenericReportCollection');
    }
}
