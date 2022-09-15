<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml\Report;

use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\ColumnSet;
use Magento\Backend\Helper\Data;

class Grid extends \Magento\Backend\Block\Widget\Grid
{
    /**
     * @var \DEG\CustomReports\Registry\CurrentCustomReport
     */
    private CurrentCustomReport $currentCustomReportRegistry;

    /**
     * @var \DEG\CustomReports\Api\CustomReportManagementInterface
     */
    private CustomReportManagementInterface $customReportManagement;

    /**
     * Grid constructor.
     *
     * @param \Magento\Backend\Block\Template\Context                $context
     * @param \Magento\Backend\Helper\Data                           $backendHelper
     * @param \DEG\CustomReports\Registry\CurrentCustomReport        $currentCustomReportRegistry
     * @param \DEG\CustomReports\Api\CustomReportManagementInterface $customReportManagement
     * @param array                                                  $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CurrentCustomReport $currentCustomReportRegistry,
        CustomReportManagementInterface $customReportManagement,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
        $this->currentCustomReportRegistry = $currentCustomReportRegistry;
        $this->customReportManagement = $customReportManagement;
    }

    public function _prepareLayout()
    {
        $customReport = $this->currentCustomReportRegistry->get();
        $genericCollection = $this->customReportManagement->getGenericReportCollection($customReport);
        $columnList = $this->customReportManagement->getColumnsList($customReport);
        $this->setCollection($genericCollection);
        $this->addColumnSet($columnList);
        $this->addGridExportBlock();
        parent::_prepareLayout();
    }

    /**
     * @param $columnList
     *
     * @return void
     */
    public function addColumnSet($columnList)
    {
        /** @var $columnSet \Magento\Backend\Block\Widget\Grid\ColumnSet */
        $columnSet = $this->_layout->createBlock(
            ColumnSet::class,
            'deg_customreports_grid.grid.columnSet'
        );
        foreach ($columnList as $columnName) {
            $formattedColumnName = str_replace(' ', '_', $columnName);
            $escapedColumName = $this->getCollection()->getConnection()->quoteIdentifier($columnName);
            if ($this->_defaultSort === false) {
                $this->_defaultSort = $escapedColumName;
            }
            /** @var $column \Magento\Backend\Block\Widget\Grid\Column */
            $data = [
                'data' => [
                    'header' => $columnName,
                    'index' => $columnName,
                    'filter_index' => new \Zend_Db_Expr($escapedColumName),
                    'type' => 'text',
                ],
            ];
            $column = $this->_layout->createBlock(
                Column::class,
                'deg_customreports_grid.grid.column.'.$formattedColumnName,
                $data
            );
            $columnSet->setChild($formattedColumnName, $column);
        }
        $this->setChild('grid.columnSet', $columnSet);
    }

    /**
     * Add the export block as a child block to the grid.
     *
     * @return $this
     * @noinspection PhpParamsInspection
     */
    public function addGridExportBlock(): Grid
    {
        $exportBlock = $this->_layout->createBlock(
            Export::class,
            'deg_customreports_grid.grid.export',
            $this->getExportArguments()
        );
        $this->setChild('grid.export', $exportBlock);
        $exportBlock->lazyPrepareLayout();

        return $this;
    }

    public function getExportArguments(): array
    {
        return [
            'data' => [
                'exportTypes' => [
                    'csv' => [
                        'urlPath' => '*/*/exportCsv',
                        'label' => 'CSV',
                    ],
                    'excel' => [
                        'urlPath' => '*/*/exportXml',
                        'label' => 'Excel XML',
                    ],
                ],
            ],
        ];
    }
}
