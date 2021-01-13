<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Block\Adminhtml\Report\Export;
use DEG\CustomReports\Block\Adminhtml\Report\Grid;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;

class ExportCsv extends Action
{
    const ADMIN_RESOURCE = 'DEG_CustomReports::automatedexport_export_report';
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Builder
     */
    private $builder;

    /**
     * @param \Magento\Backend\App\Action\Context                          $context
     * @param \Magento\Framework\App\Response\Http\FileFactory             $fileFactory
     * @param \DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Builder $builder
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        Builder $builder
    ) {
        $this->fileFactory = $fileFactory;
        $this->builder = $builder;

        parent::__construct($context);
    }

    /**
     * Export customer grid to CSV format
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function execute(): ResponseInterface
    {
        $customReport = $this->builder->build($this->getRequest());

        $this->_view->loadLayout();
        $fileName = $customReport->getReportName().'.csv';

        /** @var @var $reportGrid \DEG\CustomReports\Block\Adminhtml\Report\Grid */
        $reportGrid = $this->_view->getLayout()
            ->createBlock(Grid::class, 'report.grid');
        /** @var Export $exportBlock */
        $exportBlock = $reportGrid->getChildBlock('grid.export');

        return $this->fileFactory->create(
            $fileName,
            $exportBlock->getCsvFile(),
            DirectoryList::VAR_DIR
        );
    }
}
