<?php
declare(strict_types=1);

namespace Tests\Unit\DEG\CustomReports\Block\Adminhtml\Report;

use DEG\CustomReports\Api\CustomReportManagementInterface;
use DEG\CustomReports\Block\Adminhtml\Report\Export;
use DEG\CustomReports\Block\Adminhtml\Report\Grid;
use DEG\CustomReports\Model\CustomReport;
use DEG\CustomReports\Model\GenericReportCollection;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\ColumnSet;
use Magento\Backend\Helper\Data;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{
    /**
     * @var Grid
     */
    protected Grid $grid;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * @var Data|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $backendHelper;

    /**
     * @var CurrentCustomReport|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $currentCustomReportRegistry;

    /**
     * @var array
     */
    protected array $data;

    /**
     * @var \Magento\Framework\View\LayoutInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $layoutMock;

    /**
     * @var \DEG\CustomReports\Api\CustomReportManagementInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customReportManagement;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);
        $this->layoutMock = $this->createMock(LayoutInterface::class);
        $mathRandom = $this->createStub(\Magento\Framework\Math\Random::class);
        $request = $this->createStub(\Magento\Framework\App\Request\Http::class);
        $this->customReportManagement = $this->createMock(CustomReportManagementInterface::class);
        $this->context->method('getLayout')->willReturn($this->layoutMock);
        $this->context->method('getMathRandom')->willReturn($mathRandom);
        $this->context->method('getRequest')->willReturn($request);

        $this->backendHelper = $this->createMock(Data::class);
        $this->currentCustomReportRegistry = $this->createMock(CurrentCustomReport::class);
        $this->data = [];

        $this->grid = new Grid(
            $this->context,
            $this->backendHelper,
            $this->currentCustomReportRegistry,
            $this->customReportManagement,
            $this->data
        );
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

    public function testPrepareLayout(): void
    {
        $customReportMock = $this->createMock(CustomReport::class);
        $this->currentCustomReportRegistry->method('get')->willReturn($customReportMock);

        $genericCollectionMock = $this->createMock(GenericReportCollection::class);
        $connection = $this->createStub(AdapterInterface::class);
        $genericCollectionMock->method('getConnection')->willReturn($connection);

        $this->customReportManagement->method('getGenericReportCollection')
            ->willReturn($genericCollectionMock);

        $selectMock = $this->createMock(Select::class);
        $genericCollectionMock->method('getSelect')
            ->willReturn($selectMock);

        $this->customReportManagement->method('getColumnsList')
            ->willReturn(['test_key']);

        $columnSetBlockMock = $this->createMock(ColumnSet::class);
        $columnBlockMock = $this->createMock(Column::class);
        $exportBlockMock = $this->createMock(Export::class);
        $exportBlockMock->method('lazyPrepareLayout')->willReturn($exportBlockMock);

        $this->layoutMock->method('getChildName')->willReturn('deg_customreports_grid.grid.columnSet');
        $this->layoutMock->method('getBlock')->with('deg_customreports_grid.grid.columnSet')->willReturn($columnSetBlockMock);

        $this->layoutMock->method('createBlock')
            ->will(
                $this->returnValueMap(
                    [
                        [Column::class, 'deg_customreports_grid.grid.column.'.'test_key', [], $columnBlockMock],
                        [
                            Export::class,
                            'deg_customreports_grid.grid.export',
                            $this->grid->getExportArguments(),
                            $columnBlockMock,
                        ],
                    ]
                )
            );

        $this->grid->_prepareLayout();
    }

    public function testAddGridExportBlock(): void
    {
        $exportBlockMock = $this->createMock(Export::class);
        $this->layoutMock->method('createBlock')
            ->willReturn($exportBlockMock);

        $this->grid->addGridExportBlock();
    }
}
