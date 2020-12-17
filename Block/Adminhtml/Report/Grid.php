<?php
namespace DEG\CustomReports\Block\Adminhtml\Report;

use Magento\Framework\Registry;

class Grid extends \Magento\Backend\Block\Widget\Grid
{

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Grid constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data            $backendHelper
     * @param \Magento\Framework\Registry             $registery
     * @param array                                   $data
     */
    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Magento\Backend\Helper\Data $backendHelper,
                                Registry $registery,
                                array $data = [])
    {
        parent::__construct($context, $backendHelper, $data);
        $this->registry = $registery;
    }

    /**
     * @return \DEG\CustomReports\Block\Adminhtml\Report\Grid|void
     */
    public function _prepareLayout()
    {

        /** @var $customReport \DEG\CustomReports\Model\CustomReport */
        $customReport = $this->registry->registry('current_customreport');
        /** @var $genericCollection \DEG\CustomReports\Model\GenericReportCollection */
        $genericCollection = $customReport->getGenericReportCollection();
        $columnList = $this->getColumnListFromCollection($genericCollection);
        if (is_object($genericCollection) && $genericCollection->getSize()) {
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
        $item = $columnsCollection->getFirstItem();
        return $item;
    }

    /**
     * @param $dataItem
     */
    public function addColumnSet($dataItem)
    {
        /** @var $columnSet \Magento\Backend\Block\Widget\Grid\ColumnSet **/
        $columnSet = $this->_layout->createBlock('Magento\Backend\Block\Widget\Grid\ColumnSet', 'deg_customreports_grid.grid.columnSet');
        foreach ($dataItem->getData() as $key => $val) {
            if ($this->_defaultSort === false) {
                $this->_defaultSort = $key;
            }
            /** @var $column \Magento\Backend\Block\Widget\Grid\Column **/
            $data = [
                'data' => ['header' => $key,
                    'index' => $key,
                    'type' => 'text']
            ];
            $column = $this->_layout->createBlock('Magento\Backend\Block\Widget\Grid\Column', 'deg_customreports_grid.grid.column.' . $key, $data);
            $columnSet->setChild($key, $column);
        }
        $this->setChild('grid.columnSet', $columnSet);
    }

    /**
     * Add the export block as a child block to the grid.
     *
     * @return $this
     */
    public function addGridExportBlock()
    {
        $exportArguments = [
            'data' => [
                'exportTypes'=> [
                    'csv' => [
                        'urlPath' => '*/*/exportCsv',
                        'label' => 'CSV'
                    ],
                    'excel' => [
                        'urlPath' => '*/*/exportXml',
                        'label' => 'Excel'
                    ],
                ]
            ]
        ];

        $exportBlock = $this->_layout->createBlock('DEG\CustomReports\Block\Adminhtml\Report\Export', 'deg_customreports_grid.grid.export', $exportArguments);
        $this->setChild('grid.export', $exportBlock);
        $exportBlock->lazyPrepareLayout();
        return $this;
    }
}
