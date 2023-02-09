<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Api\AutomatedExportManagementInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterfaceFactory;
use DEG\CustomReports\Api\ExportReportServiceInterface;
use DEG\CustomReports\Model\Config\Source\ExportTypes;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;

class Export extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'DEG_CustomReports::customreport_export_report';

    public function __construct(
        Context $context,
        protected FileFactory $fileFactory,
        protected Builder $builder,
        protected ExportReportServiceInterface $exportReportService,
        protected AutomatedExportInterfaceFactory $automatedExportFactory,
        protected AutomatedExportManagementInterface $automatedExportManagement
    ) {
        parent::__construct($context);
    }

    /**
     * Export data to file in the format provided by filetype. Leverages automated export functionality.
     * See \DEG\CustomReports\Model\Config\Source\FileTypes for valid 'filetype' param values.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function execute(): ResponseInterface
    {
        $fileType = $this->getRequest()->getParam('filetype');
        $customReport = $this->builder->build($this->getRequest());

        $adhocAutomatedExport = $this->automatedExportFactory->create();
        $adhocAutomatedExport->setCustomreportIds([$customReport->getId()]);
        $adhocAutomatedExport->setFilenamePattern(AutomatedExportManagementInterface::VARIABLE_REPORTNAME);
        $adhocAutomatedExport->setExportTypes([ExportTypes::LOCAL_FILE_DROP]);
        $adhocAutomatedExport->setFileTypes([$fileType]);

        $this->exportReportService->exportAll($adhocAutomatedExport);

        return $this->fileFactory->create(
            $this->automatedExportManagement->getFilename($adhocAutomatedExport, $customReport, $fileType),
            [
                'type' => 'filename',
                'value' => $this->automatedExportManagement->getAbsoluteLocalFilepath(
                    $adhocAutomatedExport,
                    $customReport,
                    $fileType
                ),
                'rm' => true,
            ],
            DirectoryList::VAR_DIR
        );
    }
}
