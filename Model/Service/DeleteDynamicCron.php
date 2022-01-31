<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Service;

use DEG\CustomReports\Api\DeleteDynamicCronInterface;
use Magento\Framework\App\Config\ValueFactory;

class DeleteDynamicCron implements DeleteDynamicCronInterface
{
    /**
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    protected ValueFactory $configValueFactory;

    /**
     * @param \Magento\Framework\App\Config\ValueFactory $configValueFactory
     */
    public function __construct(
        ValueFactory $configValueFactory
    ) {
        $this->configValueFactory = $configValueFactory;
    }

    /**
     * @param string $automatedExportModelName
     *
     * @return void
     * @throws \Exception
     * @noinspection PhpDeprecationInspection
     */
    public function execute(string $automatedExportModelName)
    {
        $cronStringPath = "crontab/default/jobs/$automatedExportModelName/schedule/cron_expr";
        $cronModelPath = "crontab/default/jobs/$automatedExportModelName/run/model";
        $cronNamePath = "crontab/default/jobs/$automatedExportModelName/name";

        $this->configValueFactory->create()
            ->load($cronStringPath, 'path')
            ->delete();
        $this->configValueFactory->create()
            ->load($cronModelPath, 'path')
            ->delete();
        $this->configValueFactory->create()
            ->load($cronNamePath, 'path')
            ->delete();
    }
}
