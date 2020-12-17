<?php
namespace DEG\CustomReports\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinDataInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Psr\Log\LoggerInterface as Logger;

class GenericReportCollection extends \Magento\Framework\Data\Collection\AbstractDb
{
    public function __construct(EntityFactoryInterface $entityFactory,
                                Logger $logger,
                                FetchStrategyInterface $fetchStrategy,
                                AdapterInterface $connection = null,
                                ResourceConnection $resourceConnection = null)
    {
        $resourceConnection = $resourceConnection ?: ObjectManager::getInstance()->get(ResourceConnection::class);

        $connection = $resourceConnection->getConnectionByName('readonly');

        parent::__construct($entityFactory, $logger, $fetchStrategy, $connection);
    }

    /**
     * Intentionally left empty since this is a generic resource.
     */
    public function getResource()
    {
    }

    /**
     * @param JoinDataInterface $join
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @throws \Exception
     * @return $this
     */
    public function joinExtensionAttribute(
        JoinDataInterface $join,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        throw new \Exception('joinExtensionAttribute is not allowed in GenericReportCollection');
        return $this;
    }
}
