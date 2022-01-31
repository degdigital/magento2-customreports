<?php declare(strict_types=1);
namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class Factory
 */
class StreamHandlerFactory
{
    /**
     * Object Manager
     *
     * @var ObjectManagerInterface
     */
    protected ObjectManagerInterface $objectManager;

    /**
     * Construct
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create model
     *
     * @param string $className
     * @param array $data
     *
     * @return StreamHandlerInterface
     * @throws \InvalidArgumentException
     */
    public function create(string $className, array $data = []): StreamHandlerInterface
    {
        $model = $this->objectManager->create($className, $data);

        if (!$model instanceof StreamHandlerInterface) {
            throw new InvalidArgumentException(
                'Type "' . $className . '" is not instance of '.StreamHandlerInterface::class
            );
        }

        return $model;
    }
}
