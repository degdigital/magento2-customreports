<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
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
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     *
     * @return \DEG\CustomReports\Model\AutomatedExport\ExportType\StreamHandlerInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getHandlerInstances(AutomatedExportInterface $automatedExport): array
    {
        $handlerInstances = [];
        foreach ($this->handlerConfig as $handlerCode => $handler) {
            foreach ($automatedExport->getExportTypes() as $exportType) {
                if ($exportType == $handlerCode) {
                    if (empty($handler['class'])) {
                        throw new LocalizedException(__('The parameter "class" is missing.'));
                    }

                    $handlerInstances[$handlerCode] = $this->factory->create($handler['class']);
                }
            }
        }

        return $handlerInstances;
    }
}
