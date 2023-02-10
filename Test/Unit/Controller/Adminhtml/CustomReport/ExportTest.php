<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

namespace Tests\Unit\DEG\CustomReports\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Block\Adminhtml\Report\Export;
use DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\ViewInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\TestCase;

class ExportTest extends TestCase
{
    protected Export $export;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * @var FileFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactory;

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

        $this->context = $this->createMock(\Magento\Backend\Block\Template\Context::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->filesystem = $this->createMock(\Magento\Framework\Filesystem::class);
        $this->context->method('getRequest')->willReturn($this->requestMock);
        $this->context->method('getFilesystem')->willReturn($this->filesystem);

        $this->layoutMock = $this->createMock(LayoutInterface::class);

        $this->collectionFactory = $this->createMock(\Magento\Framework\Data\CollectionFactory::class);
        $this->builder = $this->createMock(Builder::class);
        $this->export = new Export($this->context, $this->collectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->export);
        unset($this->context);
        unset($this->collectionFactory);
        unset($this->builder);
    }
}
