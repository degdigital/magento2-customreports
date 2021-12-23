<?php

namespace Tests\Unit\DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Builder;
use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Report;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    /**
     * @var Report
     */
    protected $report;

    /**
     * @var Context|Mock
     */
    protected $context;

    /**
     * @var PageFactory|Mock
     */
    protected $resultPageFactory;

    /**
     * @var Builder|Mock
     */
    protected $builder;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->context->method('getRequest')->willReturn($this->requestMock);

        $this->resultPageFactory = $this->createMock(PageFactory::class);
        $this->builder = $this->createMock(Builder::class);
        $this->report = new Report($this->context, $this->resultPageFactory, $this->builder);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->report);
        unset($this->context);
        unset($this->resultPageFactory);
        unset($this->builder);
    }

    public function testExecute(): void
    {
        $pageMock = $this->getMockBuilder(\Magento\Backend\Model\View\Result\Page::class)
            ->setMethods(['setActiveMenu', 'getConfig'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultPageFactory->method('create')->willReturn($pageMock);

        $configMock = $this->createMock(\Magento\Framework\View\Page\Config::class);
        $pageMock->method('getConfig')->willReturn($configMock);

        $titleMock = $this->createMock(\Magento\Framework\View\Page\Title::class);
        $configMock->method('getTitle')->willReturn($titleMock);

        $this->report->execute();
    }
}
