<?php

namespace Tests\Unit\DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Delete;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    /**
     * @var Delete
     */
    protected $delete;

    /**
     * @var Context|Mock
     */
    protected $context;

    /**
     * @var CustomReportRepositoryInterface|Mock
     */
    protected $customReportRepository;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $redirectPageMock;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $messageManagerMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);
        $this->requestMock = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $this->context->method('getRequest')->willReturn($this->requestMock);

        $this->redirectPageMock = $this->createMock(\Magento\Framework\Controller\Result\RedirectFactory::class);
        $this->context->method('getResultRedirectFactory')->willReturn($this->redirectPageMock);

        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $this->context->method('getMessageManager')->willReturn($this->messageManagerMock);

        $this->customReportRepository = $this->createMock(CustomReportRepositoryInterface::class);
        $this->delete = new Delete($this->context, $this->customReportRepository);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->delete);
        unset($this->context);
        unset($this->customReportRepository);
    }

    public function testExecuteIdUndefined(): void
    {
        $this->requestMock->method('getParam')
            ->with('object_id')->willReturn(null);

        $redirectPageMock = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);
        $this->redirectPageMock->method('create')
            ->willReturn($redirectPageMock);

        $this->delete->execute();
    }

    public function testExecute(): void
    {
        $this->requestMock->method('getParam')
            ->with('object_id')->willReturn(1);

        $redirectPageMock = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);
        $this->redirectPageMock->method('create')
            ->willReturn($redirectPageMock);


        $this->delete->execute();
    }

    public function testExecuteFailed(): void
    {
        $this->requestMock->method('getParam')
            ->with('object_id')->willReturn(1);

        $redirectPageMock = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);
        $this->redirectPageMock->method('create')
            ->willReturn($redirectPageMock);
        
        $this->customReportRepository->method('delete')
            ->willThrowException(new \Exception('test failed deleted'));

        $this->delete->execute();
    }
}
