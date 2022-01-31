<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

namespace DEG\CustomReports\Test\Unit\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Block\Adminhtml\Report\Export;
use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Builder;
use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\ExportCsv;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\ViewInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\TestCase;

class ExportCsvTest extends TestCase
{
    /**
     * @var ExportCsv
     */
    protected ExportCsv $exportCsv;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * @var FileFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $fileFactory;

    /**
     * @var Builder|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $builder;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Framework\App\ViewInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $viewMock;

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
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->context->method('getRequest')->willReturn($this->requestMock);

        $this->viewMock = $this->createMock(ViewInterface::class);
        $this->context->method('getView')->willReturn($this->viewMock);

        $this->layoutMock = $this->createMock(LayoutInterface::class);
        $this->viewMock->method('getLayout')->willReturn($this->layoutMock);

        $this->fileFactory = $this->createMock(FileFactory::class);
        $this->builder = $this->createMock(Builder::class);
        $this->exportCsv = new ExportCsv($this->context, $this->fileFactory, $this->builder);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->exportCsv);
        unset($this->context);
        unset($this->fileFactory);
        unset($this->builder);
    }

    /**
     * @throws \Exception
     */
    public function testExecute(): void
    {
        $blockMock = $this->createMock(AbstractBlock::class);
        $this->layoutMock->method('createBlock')->willReturn($blockMock);

        $blockExport = $this->createMock(Export::class);
        $blockMock->method('getChildBlock')->willReturn($blockExport);

        $responseMock = $this->createMock(ResponseInterface::class);
        $this->fileFactory->method('create')->willReturn($responseMock);

        $this->exportCsv->execute();
    }
}
