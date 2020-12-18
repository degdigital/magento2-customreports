<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Block\Adminhtml\Report\Export;
use DEG\CustomReports\Block\Adminhtml\Report\Grid;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;

class ExportXml extends Action
{
    const ADMIN_RESOURCE = 'DEG_CustomReports::customreport_export_report';
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @var \DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder
     */
    private $builder;

    /**
     * @param \Magento\Backend\App\Action\Context                          $context
     * @param \Magento\Framework\App\Response\Http\FileFactory             $fileFactory
     * @param \DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder $builder
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        Builder $builder
    ) {
        $this->_fileFactory = $fileFactory;
        $this->builder = $builder;

        parent::__construct($context);
    }

    /**
     * Export customer grid to CSV format
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Exception
     */
    public function execute(): ResponseInterface
    {
        /** @var $reportGrid \DEG\CustomReports\Block\Adminhtml\Report\Grid */
        /** @var $exportBlock Export */

        $customReport = $this->builder->build($this->getRequest());
        $this->_view->loadLayout();
        $fileName = $customReport->getReportName().'.xml';
        $reportGrid = $this->_view->getLayout()
            ->createBlock(Grid::class, 'report.grid');
        $exportBlock = $reportGrid->getChildBlock('grid.export');

        return $this->_fileFactory->create(
            $fileName,
            $exportBlock->getExcelFile(),
            DirectoryList::VAR_DIR
        );
    }
}
