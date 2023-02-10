<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml\Report;

use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Model\Config\Source\FileTypes;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\ColumnSet;
use Magento\Backend\Helper\Data;
use Magento\Framework\Exception\LocalizedException;
use Zend_Db_Expr;

class Grid extends \Magento\Backend\Block\Widget\Grid
{
    protected $_template = 'DEG_CustomReports::widget/grid.phtml';

    /**
     * Increase the default limit to improve usefulness of "total-less" grids (allow_count_query disabled)
     */
    protected $_defaultLimit = 200;

    public function __construct(
        Context $context,
        Data $backendHelper,
        protected CurrentCustomReport $currentCustomReportRegistry,
        protected CustomReportManagementInterface $customReportManagement,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Performance note: if filters or sort orderings are present ($filtersPresent), getColumnsList triggers a fresh
     * query to get the column list. Unfortunately, it does not seem feasible to avoid this second query when filters
     * are present, because $this->_prepareCollection()'s filter initialization requires the column list, but if the
     * column list runs first (so, without the filters) then the cached collection result is unfiltered. So the filters
     * require the columns and the columns/collection result require the filters, resulting in a cyclical dependency
     * that can only avoided by running two queries, one to retrieve column list without filters, then one to retrieve
     * the collection results after filters have been processed.
     */
    public function _prepareLayout(): void
    {
        $currentCustomReport = $this->currentCustomReportRegistry->get();
        $genericCollection = $this->customReportManagement->getGenericReportCollection($currentCustomReport);
        $this->setCollection($genericCollection);
        $this->_preparePage();
        $filtersPresent = ($this->getParam($this->getVarNameFilter()) || $this->getParam($this->getVarNameSort()));
        $columnList = $this->customReportManagement->getColumnsList($currentCustomReport, $filtersPresent);
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
        /** @var ColumnSet $columnSet */
        $columnSet = $this->getChildBlock('deg_customreports_grid.grid.columnSet');
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

    /**
     * Fix 'Reset Filter' link not properly clearing sort order
     *
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareFilterButtons(): void
    {
        $this->setChild(
            'reset_filter_button',
            $this->getLayout()->createBlock(
                \Magento\Backend\Block\Widget\Button::class
            )->setData(
                [
                    'label' => __('Reset Filter'),
                    'onclick' => 'setLocation(\'' . $this->getReportUrl() . '\');',
                    'class' => 'action-reset action-tertiary'
                ]
            )->setDataAttribute(['action' => 'grid-filter-reset'])
        );
        $this->setChild(
            'search_button',
            $this->getLayout()->createBlock(
                \Magento\Backend\Block\Widget\Button::class
            )->setData(
                [
                    'label' => __('Search'),
                    'onclick' => $this->getJsObjectName() . '.doFilter()',
                    'class' => 'action-secondary',
                ]
            )->setDataAttribute(['action' => 'grid-filter-apply'])
        );
    }

    public function getReportUrl(): string
    {
        return $this->getUrl('*/*/report', ['customreport_id' => $this->currentCustomReportRegistry->get()->getId()]);
    }
}
