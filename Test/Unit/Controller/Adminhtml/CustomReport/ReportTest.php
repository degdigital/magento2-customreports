<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

namespace Tests\Unit\DEG\CustomReports\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder;
use DEG\CustomReports\Controller\Adminhtml\CustomReport\Report;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    /**
     * @var Report
     */
    protected Report $report;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var PageFactory|MockObject
     */
    protected $resultPageFactory;

    /**
     * @var Builder|MockObject
     */
    protected $builder;

    /**
     * @var RequestInterface|MockObject
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
        $messageManager = $this->createStub(ManagerInterface::class);
        $resultRedirectFactory = $this->createStub(RedirectFactory::class);
        $resultRedirect = $this->createStub(Redirect::class);

        $resultRedirectFactory->method('create')->willReturn($resultRedirect);
        $this->context->method('getRequest')->willReturn($this->requestMock);
        $this->context->method('getMessageManager')->willReturn($messageManager);
        $this->context->method('getResultRedirectFactory')->willReturn($resultRedirectFactory);

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
        $pageMock = $this->getMockBuilder(Page::class)
            ->setMethods(['setActiveMenu', 'getConfig'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultPageFactory->method('create')->willReturn($pageMock);

        $configMock = $this->createMock(Config::class);
        $pageMock->method('getConfig')->willReturn($configMock);

        $titleMock = $this->createMock(Title::class);
        $configMock->method('getTitle')->willReturn($titleMock);

        $this->report->execute();
    }
}
