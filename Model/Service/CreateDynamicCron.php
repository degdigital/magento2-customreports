<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Service;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\CreateDynamicCronInterface;
use DEG\CustomReports\Model\AutomatedExport\Cron;
use Exception;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\Config\ValueFactory;

class CreateDynamicCron implements CreateDynamicCronInterface
{
    public function __construct(
        protected ValueFactory $configValueFactory,
        protected Manager $cacheManager
    ) {
    }

    /**
     * @param AutomatedExportInterface $automatedExport
     * @return void
     * @throws Exception
     * @noinspection PhpDeprecationInspection
     */
    public function execute(AutomatedExportInterface $automatedExport): void
    {
        $automatedExportId = $automatedExport->getId();
        $automatedExportModelName = 'automated_export_' . $automatedExportId;

        $cronStringPath = "crontab/default/jobs/$automatedExportModelName/schedule/cron_expr";
        $cronModelPath = "crontab/default/jobs/$automatedExportModelName/run/model";
        $cronNamePath = "crontab/default/jobs/$automatedExportModelName/name";

        $this->configValueFactory->create()
            ->load($cronStringPath, 'path')
            ->setValue($automatedExport->getCronExpr())
            ->setPath($cronStringPath)
            ->save();
        $this->configValueFactory->create()
            ->load($cronModelPath, 'path')
            ->setValue(Cron::class . '::execute')
            ->setPath($cronModelPath)
            ->save();
        $this->configValueFactory->create()
            ->load($cronNamePath, 'path')
            ->setValue($automatedExportModelName)
            ->setPath($cronNamePath)
            ->save();

        $this->cacheManager->clean(['config']);
    }
}
