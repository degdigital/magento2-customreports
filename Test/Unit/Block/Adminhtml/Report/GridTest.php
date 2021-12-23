<?php

namespace Tests\Unit\DEG\CustomReports\Block\Adminhtml\Report;

use DEG\CustomReports\Block\Adminhtml\Report\Export;
use DEG\CustomReports\Block\Adminhtml\Report\Grid;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\ColumnSet;
use Magento\Backend\Helper\Data;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Context|Mock
     */
    protected $context;

    /**
     * @var Data|Mock
     */
    protected $backendHelper;

    /**
     * @var CurrentCustomReport|Mock
     */
    protected $currentCustomReportRegistry;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Magento\Framework\View\LayoutInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $layoutMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);
        $this->layoutMock = $this->createMock(\Magento\Framework\View\LayoutInterface::class);
        $this->context->method('getLayout')->willReturn($this->layoutMock);

        $this->backendHelper = $this->createMock(Data::class);
        $this->currentCustomReportRegistry = $this->createMock(CurrentCustomReport::class);
        $this->data = [];
        $this->grid = new Grid($this->context, $this->backendHelper, $this->currentCustomReportRegistry, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->grid);
        unset($this->context);
        unset($this->backendHelper);
        unset($this->currentCustomReportRegistry);
        unset($this->data);
    }

    public function test_prepareLayout(): void
    {
        $customReportMock = $this->createMock(\DEG\CustomReports\Model\CustomReport::class);
        $this->currentCustomReportRegistry->method('get')->willReturn($customReportMock);

        $genericCollectionMock = $this->createMock(\DEG\CustomReports\Model\GenericReportCollection::class);
        $customReportMock->method('getGenericReportCollection')
            ->willReturn($genericCollectionMock);

        $selectMock = $this->createMock(Select::class);
        $genericCollectionMock->method('getSelect')
            ->willReturn($selectMock);

        $dataObjectMock = $this->createMock(\Magento\Framework\DataObject::class);
        $genericCollectionMock->method('getFirstItem')->willReturn($dataObjectMock);


        $dataObjectMock->method('getData')
            ->willReturn([
                'test_key' => 'value_test_key'
             ]);


        $columnSetBlockMock = $this->createMock(ColumnSet::class);
        $this->layoutMock->expects(self::at(0))->method('createBlock')
            ->with(
                ColumnSet::class,
                'deg_customreports_grid.grid.columnSet'
            )->willReturn($columnSetBlockMock);

        $columnBlockMock = $this->createMock(Column::class);
        $this->layoutMock->expects(self::at(1))->method('createBlock')
            ->with(
                Column::class,
                'deg_customreports_grid.grid.column.' . 'test_key'
            )->willReturn($columnBlockMock);


        $exportBlockMock = $this->createMock(\DEG\CustomReports\Block\Adminhtml\Report\Export::class);
        $this->layoutMock->expects(self::at(4))->method('createBlock')
            ->willReturn($exportBlockMock);

        $this->grid->_prepareLayout();
    }

    public function testAddGridExportBlock(): void
    {
        $exportBlockMock = $this->createMock(\DEG\CustomReports\Block\Adminhtml\Report\Export::class);
        $this->layoutMock->method('createBlock')
            ->willReturn($exportBlockMock);

        $this->grid->addGridExportBlock();
    }
}
