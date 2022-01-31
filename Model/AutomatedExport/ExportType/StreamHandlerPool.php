<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use Magento\Framework\Exception\LocalizedException;

/**
 * @api
 */
class StreamHandlerPool implements StreamHandlerPoolInterface
{
    /**
     * @var array[]
     */
    protected array $handlerConfig = [];

    /**
     * @var StreamHandlerInterface[]
     */
    protected array $handlersInstances = [];

    /**
     * @var StreamHandlerFactory
     */
    protected StreamHandlerFactory $factory;

    /**
     * @param StreamHandlerFactory $factory
     * @param array[]              $handlers
     */
    public function __construct(
        StreamHandlerFactory $factory,
        array $handlers = []
    ) {
        $this->factory = $factory;
        $this->handlerConfig = $handlers;
    }

    /**
     * @return array[]
     */
    public function getHandlerConfig(): array
    {
        return $this->handlerConfig;
    }

    /**
     * @return array|\DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getHandlerInstances(): array
    {
        $handlerInstances = [];
        foreach ($this->handlerConfig as $handlerCode => $handler) {
            if (empty($handler['class'])) {
                throw new LocalizedException(__('The parameter "class" is missing. Set the "class" and try again.'));
            }

            $handlerInstances[$handlerCode] = $this->factory->create($handler['class']);
        }

        return $handlerInstances;
    }
}
