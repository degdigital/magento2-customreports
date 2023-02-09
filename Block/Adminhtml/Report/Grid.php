<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml\Report;

use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Model\Config\Source\FileTypes;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\ColumnSet;
use Magento\Backend\Helper\Data;
use Zend_Db_Expr;

class Grid extends \Magento\Backend\Block\Widget\Grid
{
    protected $_template = 'DEG_CustomReports::widget/grid.phtml';

    public function __construct(
        Context $context,
        Data $backendHelper,
        protected CurrentCustomReport $currentCustomReportRegistry,
        protected CustomReportManagementInterface $customReportManagement,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
    }

    public function _prepareLayout()
    {
        $customReport = $this->currentCustomReportRegistry->get();
        $genericCollection = $this->customReportManagement->getGenericReportCollection($customReport);
        $this->setCollection($genericCollection);
        $this->_preparePage();
        $columnList = $this->customReportManagement->getColumnsList($customReport);
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
        /** @var $columnSet ColumnSet */
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
            /** @var $column Column */
            $data = [
                'data' => [
                    'header' => $columnName,
                    'index' => $columnName,
                    'filter_index' => new Zend_Db_Expr($escapedColumName),
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
                    FileTypes::EXTENSION_CSV => [
                        'urlPath' => '*/*/export/filetype/' . FileTypes::EXTENSION_CSV,
                        'label' => FileTypes::LABEL_CSV,
                    ],
                    FileTypes::EXTENSION_TSV => [
                        'urlPath' => '*/*/export/filetype/' . FileTypes::EXTENSION_TSV,
                        'label' => FileTypes::LABEL_TSV,
                    ],
                    FileTypes::EXTENSION_TXT_PIPE => [
                        'urlPath' => '*/*/export/filetype/' . FileTypes::EXTENSION_TXT_PIPE,
                        'label' => FileTypes::LABEL_TXT_PIPE_DELIMITED,
                    ],
                    FileTypes::EXTENSION_XML_EXCEL => [
                        'urlPath' => '*/*/export/filetype/' . FileTypes::EXTENSION_XML_EXCEL,
                        'label' => FileTypes::LABEL_EXCEL_XML,
                    ],
                ],
            ],
        ];
    }
}
