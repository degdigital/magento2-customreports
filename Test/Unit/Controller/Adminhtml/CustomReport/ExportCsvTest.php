<?php

namespace DEG\CustomReports\Test\Unit\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder;
use DEG\CustomReports\Controller\Adminhtml\CustomReport\ExportCsv;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\View\Element\AbstractBlock;
use PHPUnit\Framework\TestCase;

class ExportCsvTest extends TestCase
{
    /**
     * @var ExportCsv
     */
    protected $exportCsv;

    /**
     * @var Context|Mock
     */
    protected $context;

    /**
     * @var FileFactory|Mock
     */
    protected $fileFactory;

    /**
     * @var Builder|Mock
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

        $this->viewMock = $this->createMock(\Magento\Framework\App\ViewInterface::class);
        $this->context->method('getView')->willReturn($this->viewMock);

        $this->layoutMock = $this->createMock(\Magento\Framework\View\LayoutInterface::class);
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

    public function testExecute(): void
    {
        $blockMock = $this->createMock(AbstractBlock::class);
        $this->layoutMock->method('createBlock')->willReturn($blockMock);

        $blockExport = $this->createMock(\DEG\CustomReports\Block\Adminhtml\Report\Export::class);
        $blockMock->method('getChildBlock')->willReturn($blockExport);

        $responseMock = $this->createMock(\Magento\Framework\App\ResponseInterface::class);
        $this->fileFactory->method('create')->willReturn($responseMock);

        $this->exportCsv->execute();
    }
}
