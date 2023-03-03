<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

namespace Tests\Unit\DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Delete;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    /**
     * @var Delete
     */
    protected Delete $delete;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * @var AutomatedExportRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $automatedExportRepository;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $redirectPageFactoryMock;

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
        $this->requestMock = $this->createMock(Http::class);
        $this->context->method('getRequest')->willReturn($this->requestMock);

        $this->redirectPageFactoryMock = $this->createMock(RedirectFactory::class);
        $this->context->method('getResultRedirectFactory')->willReturn($this->redirectPageFactoryMock);

        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->context->method('getMessageManager')->willReturn($this->messageManagerMock);

        $this->automatedExportRepository = $this->createMock(AutomatedExportRepositoryInterface::class);
        $this->delete = new Delete($this->context, $this->automatedExportRepository);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->delete);
        unset($this->context);
        unset($this->automatedExportRepository);
    }

    public function testExecuteIdUndefined(): void
    {
        $this->requestMock->method('getParam')
            ->with('object_id')->willReturn(null);

        $redirectPageMock = $this->createMock(Redirect::class);
        $redirectPageMock->method('setPath')->willReturn($redirectPageMock);
        $this->redirectPageFactoryMock->method('create')
            ->willReturn($redirectPageMock);

        $this->delete->execute();
    }

    public function testExecute(): void
    {
        $this->requestMock->method('getParam')
            ->with('object_id')->willReturn(1);

        $redirectPageMock = $this->createMock(Redirect::class);
        $redirectPageMock->method('setPath')->willReturn($redirectPageMock);
        $this->redirectPageFactoryMock->method('create')
            ->willReturn($redirectPageMock);

        $this->delete->execute();
    }

    public function testExecuteFailed(): void
    {
        $this->requestMock->method('getParam')
            ->with('object_id')->willReturn(1);

        $redirectPageMock = $this->createMock(Redirect::class);
        $redirectPageMock->method('setPath')->willReturn($redirectPageMock);
        $this->redirectPageFactoryMock->method('create')
            ->willReturn($redirectPageMock);

        $this->automatedExportRepository->method('delete')
            ->willThrowException(new Exception('test failed deleted'));

        $this->delete->execute();
    }
}
