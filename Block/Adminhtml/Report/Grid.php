<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml\Report;

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
    private $currentCustomReportRegistry;

    /**
     * Grid constructor.
     *
     * @param \Magento\Backend\Block\Template\Context         $context
     * @param \Magento\Backend\Helper\Data                    $backendHelper
     * @param \DEG\CustomReports\Registry\CurrentCustomReport $currentCustomReportRegistry
     * @param array                                           $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CurrentCustomReport $currentCustomReportRegistry,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
        $this->currentCustomReportRegistry = $currentCustomReportRegistry;
    }

    public function _prepareLayout()
    {
        $customReport = $this->currentCustomReportRegistry->get();
        $genericCollection = $customReport->getGenericReportCollection();
        $columnList = $this->getColumnListFromCollection($genericCollection);
        if (is_object($genericCollection)) {
            $this->addColumnSet($columnList);
            $this->addGridExportBlock();
            $this->setCollection($genericCollection);
        }
        parent::_prepareLayout();
    }

    /**
     * @param $collection
     *
     * @return mixed
     */
    public function getColumnListFromCollection($collection)
    {
        $columnsCollection = clone $collection;
        $columnsCollection->getSelect()->limitPage(1, 1);

        return $columnsCollection->getFirstItem();
    }

    /**
     * @param $dataItem
     */
    public function addColumnSet($dataItem)
    {
        /** @var $columnSet \Magento\Backend\Block\Widget\Grid\ColumnSet * */
        $columnSet = $this->_layout->createBlock(
            ColumnSet::class,
            'deg_customreports_grid.grid.columnSet'
        );
        foreach ($dataItem->getData() as $key => $val) {
            if ($this->_defaultSort === false) {
                $this->_defaultSort = $key;
            }
            /** @var $column \Magento\Backend\Block\Widget\Grid\Column * */
            $data = [
                'data' => [
                    'header' => $key,
                    'index' => $key,
                    'type' => 'text',
                ],
            ];
            $column = $this->_layout->createBlock(
                Column::class,
                'deg_customreports_grid.grid.column.'.$key,
                $data
            );
            $columnSet->setChild($key, $column);
        }
        $this->setChild('grid.columnSet', $columnSet);
    }

    /**
     * Add the export block as a child block to the grid.
     *
     * @return $this
     */
    public function addGridExportBlock(): Grid
    {
        $exportArguments = [
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

        $exportBlock = $this->_layout->createBlock(
            Export::class,
            'deg_customreports_grid.grid.export',
            $exportArguments
        );
        $this->setChild('grid.export', $exportBlock);
        $exportBlock->lazyPrepareLayout();

        return $this;
    }
}
